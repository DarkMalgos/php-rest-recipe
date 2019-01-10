<?php

namespace App\Controllers;

use PDO;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class RecipeController
 *
 * @package \Src\Controllers
 */
class RecipeController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getRecipe($id)
    {
        $recipe = $this->container->get('db')->prepare(
            "SELECT * FROM recipes WHERE id = :id"
        );
        $recipe->execute(['id' => $id]);
        $recipe = $recipe->fetchObject();

        $ingredients = $this->container->get('db')->prepare(
            "SELECT * FROM ingredients WHERE id_recipe = :id"
        );
        $ingredients->execute(['id' => $id]);
        $recipe->ingredients = $ingredients->fetchAll(PDO::FETCH_OBJ);

        $steps = $this->container->get('db')->prepare(
            "SELECT * FROM steps WHERE id_recipe = :id"
        );
        $steps->execute(['id' => $id]);
        $recipe->steps = $steps->fetchAll(PDO::FETCH_OBJ);
        return $recipe;
    }

    public function add_ingredient($ingredient, $response, $id_recipe)
    {
        if (!isset($ingredient)) {
            return $response->withStatus(404);
        }

        $new_ingredient = $this->container->get('db')->prepare(
            "INSERT INTO ingredients(name, quantity, id_recipe) VALUES (:name, :quantity, :id_recipe)"
        );

        $new_ingredient->execute([
            'name' => $ingredient['name'],
            'quantity' => $ingredient['quantity'],
            'id_recipe' => $id_recipe
        ]);
    }

    public function add_step($step, $nb_step, $response, $id_recipe)
    {
        if (!isset($step)) {
            return $response->withStatus(404);
        }

        $new_step = $this->container->get('db')->prepare(
            "INSERT INTO steps(nb_step, step, id_recipe) VALUES (:nb_step, :step, :id_recipe)"
        );

        $new_step->execute([
            'nb_step' => $nb_step,
            'step' => $step,
            'id_recipe' => $id_recipe
        ]);
    }

    public function up_ingredient($ingredient, $response, $id_recipe)
    {
        if (!isset($ingredient)) {
            return $response->withStatus(404);
        }

        if ($ingredient->length > 1) {
            $new_ingredient = $this->container->get('db')->prepare(
                "UPDATE ingredients SET name = :name, quantity = :quantity WHERE id_recipe = :id_recipe AND id = :id"
            );

            $new_ingredient->execute([
                'name' => $ingredient['name'],
                'quantity' => $ingredient['quantity'] . ' ' . $ingredient['unit'],
                'id_recipe' => $id_recipe,
                'id' => $ingredient['id']
            ]);

        } else {
            $ingredients = $this->container->get('db')->prepare(
                "DELETE FROM ingredients WHERE id = :id"
            );
            $ingredients->execute([
                'id' => $ingredient['id']
            ]);
        }
    }

    public function up_step($step, $response, $id_recipe)
    {
        if (!isset($step)) {
            return $response->withStatus(404);
        }

        $new_step = $this->container->get('db')->prepare(
            "UPDATE steps SET nb_step = :nb_step, step = :step WHERE id_recipe = :id_recipe AND id = :id"
        );

        $new_step->execute([
            'nb_step' => $step['nb_step'],
            'step' => $step['name'],
            'id_recipe' => $id_recipe,
            'id' => $step['id']
        ]);
    }

    public function post($request, $response, $args)
    {
        $body = $request->getParsedBody();
        echo $body;
        if (!isset($body['recipe']) || !isset($body['ingredients']) || !isset($body['steps'])) {
            return $response->withStatus(404);
        }

        $recipe = $this->container->get('db')->prepare(
            "INSERT INTO recipes(title) VALUES (:title)"
        );
        $recipe->execute([
            'title' => $body['recipe']
        ]);

        $id_recipe = $this->container->get('db')->lastInsertId();

        foreach ($body['ingredients'] as $value) {
            $this->add_ingredient($value, $response, $id_recipe);
        }

        foreach ($body['steps'] as $key => $value) {
            $key += 1;
            $this->add_step($value, $key, $response, $id_recipe);
        }

        $recipe = $this->getRecipe($id_recipe);
        return $response->withStatus(200)->withJson($recipe);
    }

    public function get($request, $response, $args)
    {
        $recipe = $this->getRecipe($args['id']);

        if (!$recipe) {
            return $response->withStatus(404);
        }

        return $response->withStatus(200)->withJson($recipe);
    }

    public function all($request, $response, $args)
    {
        $recipes = $this->container->get('db')->prepare(
            "SELECT * FROM recipes"
        );
        $recipes->execute();

        $recipes = $recipes->fetchAll(PDO::FETCH_OBJ);

        return $response->withStatus(200)->withJson($recipes);
    }

    public function update($request, $response, $args)
    {
        $body = $request->getParsedBody();

        if (!isset($body['recipe']) || !isset($body['recipe']['ingredients']) || !isset($body['recipe']['steps'])) {
            return $response->withStatus(404);
        }

        $recipe = $this->container->get('db')->prepare(
            "UPDATE recipes SET title = :title WHERE id = :id"
        );
        $recipe->execute([
            'title' => $body['recipe']['title'],
            'id' => $body['recipe']['id']
        ]);

        foreach ($body['recipe']['ingredients'] as $value) {
            $this->up_ingredient($value, $response, $body['recipe']['id']);
        }

        foreach ($body['recipe']['steps'] as $value) {
            $this->up_step($value, $response, $body['recipe']['id']);
        }


    }

    public function delete($request, $response, $args)
    {
        $recipe = $this->container->get('db')->prepare(
            "DELETE FROM recipes WHERE id = :id"
        );
        $recipe->execute(['id' => $args['id']]);

        $ingredients = $this->container->get('db')->prepare(
            "DELETE FROM ingredients WHERE id_recipe = :id"
        );
        $ingredients->execute(['id' => $args['id']]);

        $steps = $this->container->get('db')->prepare(
            "DELETE FROM steps WHERE id_recipe = :id"
        );
        $steps->execute(['id' => $args['id']]);
    }
}
