<?php
declare(strict_types=1);

namespace BladL\BestGraphQL\Events;

use BladL\BestGraphQL\FieldResolver\FieldResolverInfo;
use BladL\BestGraphQL\FieldResolver\FieldResolverResult;
use function array_filter;

final class EventCollection
{
    /**
     * @var EventListenerInterface[]
     */
    private array $events = [];

    public function add(EventListenerInterface $eventListener): void
    {
        $this->events[] = $eventListener;
    }

    /**
     * @internal
     */
    public function executeBeforeFieldListener(FieldResolverInfo $info):void {
       foreach ($this->events as $listener) {
         if ($listener instanceof BeforeFieldResolvedListenerInterface) {
             $listener->beforeFieldResolve($info);
         }
       }
    }
    /**
     * @internal
     */
    public function executeAfterFieldListener(FieldResolverResult $result):void {
        foreach ($this->events as $listener) {
            if ($listener instanceof AfterFieldResolvedListenerInterface) {
                $listener->afterFieldResolved($result);
            }
        }
    }

    /**
     * @param EventListenerInterface[] $arr
     * @param EventListenerInterface $listener
     * @return void
     */
    private function removeFromArray(array &$arr, EventListenerInterface $listener): void
    {
        $arr = array_filter($arr, static function (EventListenerInterface $element) use ($listener) {
            return $element !== $listener;
        });
    }

    public function remove(EventListenerInterface $listener): void
    {
        $this->removeFromArray($this->events,$listener);
    }
}
