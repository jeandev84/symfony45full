<?php
namespace App\Controller\Admin\CRUD;

use App\Manager\UserManager;
use App\Manager\VideoManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class VideoCrudController extends AbstractController
{

     /**
      * @var VideoManager
     */
     protected $videoManager;



     /**
      * @var UserManager
     */
     protected $userManager;



     /**
      * @param VideoManager $videoManager
      * @param UserManager $userManager
     */
     public function __construct(VideoManager $videoManager, UserManager $userManager)
     {
          $this->videoManager = $videoManager;
          $this->userManager  = $userManager;
     }



     /**
      * @Route("/admin/videos/{id}", name="admin.videos.show", methods={"GET"}, requirements={"id": "\d+"})
     */
     public function show(int $id): Response
     {
         $video = $this->videoManager->getVideoById($id);

         // add exception here
         // dump($video->getUser());
         // dump($video->getUser()->getName());
         // dump($this->videoManager->findVideo(1));


         $user = $this->userManager->findUserById(1);


         foreach ($user->getVideos() as $video) {
              dump($video->getTitle());
         }

         return $this->render('admin/video/show.html.twig', [
             'video' => $video
         ]);
     }


     /**
      * @param int $id
      * @return void
     */
     public function showUserVideos(int $id)
     {

     }
}