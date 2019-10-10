<?php
namespace Adherents\Helper;

use Zend\View\Helper\AbstractHelper;
use Adherents\Entity\VcApec;

/**
 * This view helper class displays login informatioon
 */
class AdminHelper extends AbstractHelper
{
    /**
     * entityManager; service.
     * @var array
     */
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function renderApec()
    {
        $apecs = $this->entityManager->getRepository(VcApec::class)->findAll();


        $result = '<select id="iapec" class="multis bg-transparent" name="apec[]" multiple>';

        foreach ($apecs as $apec) {
            $result .= '<option value="'.$apec->getId().'">';
            $result .= $apec->getIntitule();
            $result .= '</option>';
        }

        $result .= '</select>';

        return $result;
    }
}
