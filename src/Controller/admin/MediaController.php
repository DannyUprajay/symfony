<?php

namespace App\Controller\admin;

use App\Entity\Media;
use App\Form\MediaSearchType;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/admin/media')]
class MediaController extends AbstractController
{


    public function __construct
    (
        private MediaRepository $mediaRepository,
        private EntityManagerInterface $entityManager,
        private PaginatorInterface $paginator,
    )
    {

    }

    #[Route('/', name: 'app_media')]
    public function index(Request $request): Response
    {
        $qb = $this->mediaRepository->getQbAll();


        $form = $this->createForm(MediaSearchType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();

            if($data['mediaTitle']!== null){
//                WHERE title LIKE "%{ma_recherche}%"
            $qb->andWhere('m.title LIKE :title')
                ->setParameter('title', '%' . $data['mediaTitle'] . '%');
            }

            if($data["userEmail"] !== null){
//                INNER JOIN user on media.user_id = user.id
//                WHERE user.email = {ma recherche}
                $qb->innerJoin('m.user', 'u')
                    ->andWhere('u.email = :email')
                    ->setParameter('email', $data['userEmail']);
            }
            if($data["date"] !== null){
                $qb->andWhere('m.createdAt > :createdAt')
                    ->setParameter('createdAt', $data['date']);
            }


        }

        $pagination = $this->paginator->paginate(
            $qb,
            $request->query->getInt('page', '1'),
            15
        );

        return $this->render('media/index.html.twig', [
            'medias' => $pagination,
            'form' => $form->createView()
        ]);
    }

    #[Route('/show/{id}', name: 'app_media_show')]
    public function detail($id): Response
    {
        $mediaEntities = $this->mediaRepository->find($id);

        if($mediaEntities === null){
            return $this->redirectToRoute('app_media');
        }

        return $this->render('media/show.html.twig', [
            'media' => $mediaEntities
        ]);
    }

    #[Route('/new', name: 'app_media_new')]
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        /**
         * récupere l'utilisateur connecté
         * soit une entité User (si connecté)
         * soit null
         */
        $user = $this->getUser();

        $uploadDirectory = $this->getParameter('upload_file');



        $mediaEntity = new Media();
        $mediaEntity->setUser($user);
        $mediaEntity->setCreatedAt(new \DateTime());

        $form = $this->createForm(MediaType::class, $mediaEntity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $slug = $slugger->slug($mediaEntity->getTitle());
            $mediaEntity->setSlug($slug);

            $file = $form->get('file')->getData();

            if($file){
                /** @var UploadedFile $file */
                //ex: ma super image
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

                //ex: ma-super-image
                $safeFileName = $slugger->slug($originalFileName);

                //ex: ma-super-image-45461215dadafad.jpg
                $newFileName = $safeFileName . '-' . uniqid() . '-' . $file->guessExtension();

                //je bouge le média dans le dossier d'upload avec son nouveau nom
                try{
                    $file->move(
                        $this->getParameter('upload_file'),
                        $newFileName
                    );
                    //je donne le chemin du ficher à mon média
                    $mediaEntity->setFilePath($newFileName);
                }catch(FileException $e){

                }
            }
            $this->entityManager->persist($mediaEntity);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_media');
        }


        return $this->render('media/new.html.twig', [
            'formMedia' =>$form->createView()
        ]);
    }

}
