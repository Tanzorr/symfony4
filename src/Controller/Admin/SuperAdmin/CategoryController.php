<?php


namespace App\Controller\Admin\SuperAdmin;


use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\CategoryTreeAdminList;
use App\Form\CategoryType;



class CategoryController extends AbstractController
{
    /**
     * @Route("/su/categories", name="categories", methods={"GET","POST"})
     */
    public function categories(CategoryTreeAdminList $categories, Request $request)
    {
        $categories->getCategoryList($categories->buildTree());

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $is_invalid = null;

        if ($this->saveCategory($category, $form, $request))
        {
            return $this->redirectToRoute('categories');
        }

        elseif ($request->isMethod('post'))
        {
            $is_invalid = 'is-invalid';
        }

        return $this->render('admin/categories.html.twig', [
            'categories' => $categories->categorylist,
            'form'=>$form->createView(),
            'is_invalid'=>$is_invalid

        ]);
    }

    private function saveCategory($category, $form, $request)
    {

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setName($request->request->get('category')['name']);
            $repository = $this->getDoctrine()->getRepository(Category::class);
            $parent = $repository->find($request->request->get('category')['parent']);
            $category->setParent($parent);
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($category);
            $entityManger->flush();

            return true;
        }
        return false;

    }

}