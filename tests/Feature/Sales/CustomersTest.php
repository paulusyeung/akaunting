<?php

namespace Tests\Feature\Sales;

use App\Jobs\Common\CreateContact;
use App\Models\Auth\User;
use App\Models\Common\Contact;
use Tests\Feature\FeatureTestCase;

class CustomersTest extends FeatureTestCase
{
	public function testItShouldSeeCustomerListPage()
	{
		$this->loginAs()
			->get(route('customers.index'))
			->assertStatus(200)
			->assertSeeText(trans_choice('general.customers', 2));
	}

	public function testItShouldSeeCustomerCreatePage()
	{
		$this->loginAs()
			->get(route('customers.create'))
			->assertStatus(200)
			->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.customers', 1)]));
	}

	public function testItShouldCreateCustomer()
	{
		$request = $this->getRequest();

		$this->loginAs()
			->post(route('customers.store'), $request)
			->assertStatus(200);

		$this->assertFlashLevel('success');

        $this->assertDatabaseHas('contacts', $request);
	}

	public function testItShouldCreateCustomerWithUser()
	{
        $request = $this->getRequestWithUser();

		$this->loginAs()
			->post(route('customers.store'), $request)
			->assertStatus(200);

		$this->assertFlashLevel('success');

		$user = User::where('email', $request['email'])->first();

		$this->assertNotNull($user);
		$this->assertEquals($request['email'], $user->email);
	}

	public function testItShouldSeeCustomerDetailPage()
	{
		$request = $this->getRequest();

		$customer = $this->dispatch(new CreateContact($request));

		$this->loginAs()
			->get(route('customers.show', $customer->id))
			->assertStatus(200)
			->assertSee($customer->email);
	}

	public function testItShouldSeeCustomerUpdatePage()
	{
		$request = $this->getRequest();

		$customer = $this->dispatch(new CreateContact($request));

		$this->loginAs()
			->get(route('customers.edit', $customer->id))
			->assertStatus(200)
			->assertSee($customer->email);
	}

	public function testItShouldUpdateCustomer()
	{
		$request = $this->getRequest();

		$customer = $this->dispatch(new CreateContact($request));

        $request['email'] = $this->faker->safeEmail;

		$this->loginAs()
			->patch(route('customers.update', $customer->id), $request)
			->assertStatus(200)
			->assertSee($request['email']);

		$this->assertFlashLevel('success');

        $this->assertDatabaseHas('contacts', $request);
	}

	public function testItShouldDeleteCustomer()
	{
        $request = $this->getRequest();

		$customer = $this->dispatch(new CreateContact($request));

		$this->loginAs()
			->delete(route('customers.destroy', $customer->id))
			->assertStatus(200);

		$this->assertFlashLevel('success');

        $this->assertSoftDeleted('contacts', $request);

	}

	public function testItShouldNotDeleteCustomerIfHasRelations()
	{
		$this->assertTrue(true);
		//TODO : This will write after done invoice and revenues tests.
	}

    public function getRequest()
    {
        return factory(Contact::class)->states('customer', 'enabled')->raw();
    }

	public function getRequestWithUser()
	{
		$password = $this->faker->password;

		return $this->getRequest() + [
			'create_user' => 'true',
			'locale' => 'en-GB',
			'password' => $password,
			'password_confirmation' => $password,
		];
	}
}
