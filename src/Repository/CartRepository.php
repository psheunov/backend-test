<?php

declare(strict_types=1);

namespace Raketa\BackendTestTask\Repository;

use CartFactory;
use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\Factory\CustomerFactory;
use Raketa\BackendTestTask\Infrastructure\ConnectorInterface;

class CartRepository implements CartRepositoryInterface
{
    public function __construct(
        private readonly ConnectorInterface  $redis,
        private readonly LoggerInterface $logger
    )
    {
    }


    public function getCurrentCart(): Cart
    {
        try {
            $cart = $this->redis->get(session_id());
        } catch (\Exception $e) {
            $this->logger->error('Ошибка получения корзины', [
                'exception' => $e->getMessage()
            ]);
        }

        return $cart ?? CartFactory::createEmpty(CustomerFactory::createFromSession());
    }

    /**
     * @param Cart $cart
     * @return void
     * @throws Exception
     */
    public function save(Cart $cart): void
    {
        try {
            $this->redis->set($cart->uuid, $cart);
        } catch (Exception $e) {
            $this->logger->error('Ошибка сохранения корзины', [
                'cartId' => $cart->uuid,
                'exception' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}