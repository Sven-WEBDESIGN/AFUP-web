<?php

namespace AppBundle\Compta\Importer;

class Factory
{
    /**
     * @param string $code
     * @param string $code
     * @return Importer
     */
    public function create($filePath, $code)
    {
        switch ($code) {
            case CreditMutuel::CODE:
                $importer = new CreditMutuel();
                break;
            case CaisseEpargne::CODE:
                $importer = new CaisseEpargne();
                break;
            default:
                throw new \InvalidArgumentException("Unknown importer for code '$code'");
        }

        $importer->initialize($filePath);

        return $importer;
    }
}
