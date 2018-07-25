<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Melihovv\Base64ImageDecoder\Base64ImageDecoder;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Serializer\Normalizer\DataUriNormalizer;

/**
 * @Route("/image")
 */
class ImageController extends Controller
{
    /**
     * @Route("/{limit}/{offset}", name="image_index", methods="GET")
     */
    public function index(ImageRepository $imageRepository, SerializerInterface $serializer, $limit,$offset): JsonResponse
    {

        $image_repository = $imageRepository->findBy(
            array(),
            array('id' => 'DESC'),
            $limit,
            $offset
        );

        $data = $serializer->serialize($image_repository, 'json');
        return new JsonResponse($data , 200);

    }

    /**
     * @Route("/new", name="image_new", methods="POST")
     */
    public function new(Request $request,ImageRepository $imageRepository, SerializerInterface $serializer, ValidatorInterface $validator): Response
    {

        $data = json_decode(
            $request->getContent(),
            true
        );



        try {
            $decoder = new Base64ImageDecoder($data['image'], $allowedFormats = ['jpeg', 'png', 'gif']); // exception is thrown inside the constructor
        } catch (\Exception $e) {
            return new Response('Select a .jpeg .png or .gif file',422);
        }

        $random_string = strtr(base64_encode(openssl_random_pseudo_bytes(40)), "+/=", "XXX");
        $image_path = '\images\\'.$random_string.'.'.$decoder->getFormat();

        $image = new Image();
        $image->setName($data['name']);
        $image->setImagePath($image_path);
        $image->setDateCreated(new \DateTime('now'));

        $errors = $validator->validate($image);
        if (count($errors) > 0) {
            return new Response($errors[0]->getMessage(),422);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($image);
        $em->flush();
        file_put_contents($this->get('kernel')->getProjectDir().'\public'.$image_path, $decoder->getDecodedContent());

        return new Response('' , 201);

    }


    public function show(Image $image): Response
    {
        return new Response('' , 404);
    }

    public function edit(Request $request, Image $image): Response
    {
        return new Response('' , 404);
    }

    public function delete(Request $request, Image $image): Response
    {
        return new Response('' , 404);
    }
}
