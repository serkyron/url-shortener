<?php

namespace App\Controller;

use App\Entity\UrlPair;
use App\Validator\Constraints\NoNonWordChars;
use App\Validator\Constraints\UnusedShortUrl;
use App\Validator\Constraints\ValidUrl;
use Doctrine\ORM\EntityManager;
use PharIo\Manifest\Url;
use phpDocumentor\Reflection\Types\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Psr\Log\LoggerInterface;

class ShortenerController extends AbstractController
{
    /**
     * Corresponds to application home page
     *
     * @Route("/", name="home")
     * @Method({"GET"})
     */
    public function index()
    {
        $requestedUrlMaxLength = getenv('REQUESTED_URL_MAX_LENGTH');
        $host = getenv('APP_URL');
        $requestedUrlMask = implode("N", array_fill(0, $requestedUrlMaxLength, ""));

        return $this->render('home.html.twig', [
            'host' => $host,
            'requestedUrlMask' => $requestedUrlMask
        ]);
    }

    /**
     * Redirects users from short URL to the original URL.
     * If no record with such short url found in the DB, then 404 page returned.
     *
     * @Route("/{slug}")
     * @param $slug
     * @param LoggerInterface $logger
     * @return RedirectResponse
     */
    public function navigate($slug, LoggerInterface $logger)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $urlPair = $entityManager->getRepository(UrlPair::class)
            ->findByShortUrl($slug);

        if (!$urlPair) {
            $logger->info("Requested redirection to non-existent $slug");
            throw new NotFoundHttpException('Page not found');
        }

        $urlPair->setUsedTimes($urlPair->getUsedTimes() + 1);

        //update usage counter
        $entityManager->persist($urlPair);
        $entityManager->flush();

        return $this->redirect($urlPair->getLongUrl());
    }

    /**
     * @Route("/api/shorten/url", name="shorten")
     * @Method({"POST"})
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param LoggerInterface $logger
     * @return JsonResponse
     */
    public function shorten(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, LoggerInterface $logger)
    {
        $violations = $this->validateShortenRequest($request, $validator);

        if (count($violations))
        {
            $errors = $serializer->serialize($violations, 'json');
            $logger->debug("Validation errors: $errors");
            return JsonResponse::fromJsonString($errors, 400);
        }

        $entityManager = $this->getDoctrine()->getManager();

        //create a new url pair
        $urlPair = new UrlPair();
        $urlPair->setLongUrl($request->get('long_url'));
        $urlPair->setCreatedAt(new \DateTime());

        if ($request->request->has('requested'))
            $urlPair->setShortUrl($request->get('requested'));
        else
            $urlPair->setShortUrl($this->getUniqueShortUrl($entityManager, $logger));

        //save the new url pair
        $entityManager->persist($urlPair);
        $entityManager->flush();

        return new JsonResponse([
            'short_url' => getenv('APP_URL').'/'.$urlPair->getShortUrl()
        ]);
    }

    private function getUniqueShortUrl(EntityManager $entityManager, LoggerInterface $logger)
    {
        $rep = $entityManager->getRepository(UrlPair::class);
        $codeLength = getenv('SHORT_URL_LENGTH');

        do {
            $code = bin2hex(random_bytes($codeLength));
            $pair = $rep->findByShortUrl($code);
            $logger->debug("Trying to generate a unique slug. Generated code: $code");;
        }
        while($pair);

        return $code;
    }

    private function validateShortenRequest(Request $request, ValidatorInterface $validator)
    {
        $constraints = [
            'long_url' => [
                new NotBlank(),
                new ValidUrl()
            ]
        ];

        if ($request->request->has('requested'))
        {
            $constraints['requested'] = [
                new Length([
                    'min' => getenv('REQUESTED_URL_MIN_LENGTH'),
                    'max' => getenv('REQUESTED_URL_MAX_LENGTH')
                ]),
                new NoNonWordChars(),
                new UnusedShortUrl()
            ];
        }

        return $validator->validate($request->request->all(), new Collection($constraints));
    }
}