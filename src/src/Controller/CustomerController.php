<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#require_once   '../vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


/**
 * Class CustomerSiteController
 * @package App\Controller
 *
 * @Route(path="/customer")
 */
class CustomerController
{
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Route("/add", name="add_customer", methods={"POST"})
     */
    public function addCustomer(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $phoneNumber = $data['phoneNumber'];

        if (empty($firstName) || empty($lastName) || empty($email) || empty($phoneNumber)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->customerRepository->saveCustomer($firstName, $lastName, $email, $phoneNumber);

        // RabbitMq notification
        $connection = new AMQPStreamConnection('172.19.0.2', 5672, 'rabbitmq', 'rabbitmq');
        $channel = $connection->channel();

        $payload = json_encode(array($id,$email));
        $msg = new AMQPMessage($payload);
        $channel->basic_publish($msg, '', 'user_queue');

        $channel->close();
        $connection->close();


        return new JsonResponse(['status' => 'Customer added, message to RabbitMQ sent!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/get/{id}", name="get_one_customer", methods={"GET"})
     */
    public function getOneCustomer($id): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);
        $data = [
            'id' => $customer->getId(),
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName(),
            'email' => $customer->getEmail(),
            'phoneNumber' => $customer->getPhoneNumber(),
        ];


        return new JsonResponse(['customer' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/get-all", name="get_all_customers", methods={"GET"})
     */
    public function getAllCustomers(): JsonResponse
    {
        $customers = $this->customerRepository->findAll();
        $data = [];
        $msg = "message";

        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer->getId(),
                'firstName' => $customer->getFirstName(),
                'lastName' => $customer->getLastName(),
                'email' => $customer->getEmail(),
                'phoneNumber' => $customer->getPhoneNumber(),
            ];
        }

        return new JsonResponse(['customers' => $data], Response::HTTP_OK);
    }

    /**
     * @Route("/update/{id}", name="update_customer")
     */
    public function updateCustomer($id, Request $request): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        $this->customerRepository->updateCustomer($customer, $data);

        return new JsonResponse(['status' => 'customer updated!']);
    }

    /**
     * @Route("/delete/{id}", name="delete_customer", methods={"DELETE"})
     */
    public function deleteCustomer($id): JsonResponse
    {
        $customer = $this->customerRepository->findOneBy(['id' => $id]);

        $this->customerRepository->removeCustomer($customer);

        return new JsonResponse(['status' => 'customer deleted']);
    }


    /**
     * @Route("/consumer", name="consumer")
     */
    public function consumer(): JsonResponse
    {
      $connection = new AMQPStreamConnection('172.19.0.2', 5672, 'rabbitmq', 'rabbitmq');
      $channel = $connection->channel();

      $channel->queue_declare('user_queue', false, true, false, false);



      $callback = function ($msg) {
          echo ' [x] Received ', $msg->body, "\n";
      };

      $channel->basic_consume('user_queue', '', false, true, false, false, $callback);

      // while ($channel->is_consuming()) {
      //     $channel->wait();
      // }

      $channel->close();
      $connection->close();


      return new JsonResponse(['Messages consumed!']);
    }




}
