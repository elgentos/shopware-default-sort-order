<?php declare(strict_types=1);

namespace ElgentosDefaultSortOrder\Subscriber;

use Shopware\Core\Content\Product\Events\ProductListingResultEvent;
use Shopware\Core\Content\Product\SalesChannel\Listing\ProductListingFeaturesSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class ProductListingResultSubscriber implements EventSubscriberInterface
{
    /**
     * @var SystemConfigService
     */
    private SystemConfigService $systemConfigService;

    public function __construct(
        SystemConfigService $systemConfigService
    ) {
        $this->systemConfigService = $systemConfigService;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            ProductListingResultEvent::class    => 'handleResult'
        ];
    }

    /**
     * @param ProductListingResultEvent $event
     */
    public function handleResult(ProductListingResultEvent $event): void
    {
        $request = $event->getRequest();

        $defaultSortOrder = $this->systemConfigService->get('ElgentosDefaultSortOrder.config.defaultSortOrder') ?? ProductListingFeaturesSubscriber::DEFAULT_SORT;

        /* Sorting is not selected in frontend */
        if (!$request->get('order')) {
            $event->getResult()->setSorting($defaultSortOrder);
        }
    }

}
