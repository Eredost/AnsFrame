<?php

namespace App\Controller\Backend;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class QuestionController
 *
 * @package App\Controller\Backend
 *
 * @Route("/admin/question",
 *     name="admin_question_")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/{questionId}/toggle",
     *     name="toggle",
     *     requirements={"questionId": "\d+"},
     *     methods={"GET"})
     * @ParamConverter("question", options={"id" = "questionId"})
     * @IsGranted("ROLE_MODERATOR")
     *
     * @param Question               $question
     * @param EntityManagerInterface $manager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function toggle(Question $question, EntityManagerInterface $manager)
    {
        $question->toggle();
        $manager->flush();

        return $this->redirectToRoute('question_show', [
            'questionId' => $question->getId(),
        ]);
    }

    /**
     * @Route("/{questionId}",
     *     name="delete",
     *     requirements={"questionId": "\d+"},
     *     methods={"DELETE"})
     * @ParamConverter("question", options={"id": "questionId"})
     * @IsGranted("QA_EDIT", subject="question")
     *
     * @param Question               $question
     * @param EntityManagerInterface $manager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Question $question, EntityManagerInterface $manager)
    {
        $manager->remove($question);
        $manager->flush();

        return $this->redirectToRoute('home');
    }
}