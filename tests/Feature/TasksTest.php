<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TasksTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_read_all_the_tasks()
    {
        //Given we have task in the database
        $task = factory('App\Task')->create();

        //When user visit the tasks page
        $response = $this->get('/tasks');

        //He should be able to read the task
        $response->assertSee($task->title);
    }

    /** @test */
    public function a_user_can_read_single_task()
    {
        //Given we have task in the database
        $task = factory('App\Task')->create();
        //When user visit the task's URI
        $response = $this->get('/tasks/'.$task->id);
        //He can see the task details
        $response->assertSee($task->title)
            ->assertSee($task->description);
    }

    /** @test */
    public function authenticated_users_can_create_a_new_task()
    {
        //Given we have an authenticated user
        $this->actingAs(factory('App\User')->create());
        //And a task object
        $task = factory('App\Task')->make();
        //When user submits post request to create task endpoint
        $this->post('/tasks/create',$task->toArray());
        //It gets stored in the database
        $this->assertEquals(1, Task::all()->count());
    }

    /** @test */
    public function unauthenticated_users_cannot_create_a_new_task()
    {
        //Given we have a task object
        $task = factory('App\Task')->make();
        //When unauthenticated user submits post request to create task endpoint
        // He should be redirected to login page
        $this->post('/tasks/create',$task->toArray())
            ->assertRedirect('/login');
    }
}
