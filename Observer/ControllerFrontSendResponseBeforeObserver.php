<?php


namespace ADM\QuickDevBar\Observer;


use ADM\QuickDevBar\Helper\AccessChecker;
use ADM\QuickDevBar\Helper\Register;
use Laminas\Http\Headers;
use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\App\Response\Http as ResponseHttp;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ControllerFrontSendResponseBeforeObserver implements ObserverInterface
{
    /**
     * @var Register
     */
    private $qdbHelperRegister;
    
    /**
     * @var AccessChecker
     */
    private $accessChecker;


    public function __construct(Register $qdbHelperRegister,
                                AccessChecker $accessChecker)
    {
        $this->qdbHelperRegister = $qdbHelperRegister;
        $this->accessChecker = $accessChecker;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        /** @var RequestHttp $request */
        //TODO: Show $request = $observer->getRequest();

        /** @var ResponseHttp $response */
        //TODO: Show $response = $observer->getResponse();
        $response = $observer->getResponse();

        /** @var Headers $header */
       //TODO: Show $response->getHeaders()

        //Remove QDB trace
        if(!$this->accessChecker->isToolbarAccessAllowed()) {
            $newContent = preg_replace('/<!-- Start:ADM_QuickDevBar(?s).*End:ADM_QuickDevBar -->/', '', $response->getContent());
            $response->setContent($newContent);
        }
    }



}
