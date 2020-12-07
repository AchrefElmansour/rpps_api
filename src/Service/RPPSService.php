<?php

namespace App\Service;

use App\Entity\Drug;
use App\Entity\RPPS;
use App\Repository\DrugRepository;
use App\Repository\RPPSRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Contains all useful methods to process files and import them into database.
 */
class RPPSService extends ImporterService
{


    /**
     * @var EntityManagerInterface
     */
    protected $em;


    /**
     * @var string
     */
    protected $cps;

    /**
     * @var string
     */
    protected $rpps;


    /**
     * RPPSService constructor.
     * @param string $cpsUrl
     * @param string $rppsUrl
     * @param FileProcessor $fileProcessor
     * @param EntityManagerInterface $em
     */
    public function __construct(string $cpsUrl,string $rppsUrl,FileProcessor $fileProcessor,EntityManagerInterface $em)
    {
        parent::__construct(RPPS::class,$fileProcessor,$em);

        $this->rpps = $rppsUrl;
        $this->cps = $cpsUrl;

    }

    /**
     * @param array $data
     * @param string $type
     * @return RPPS|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function processData(array $data,string $type) : ?RPPS
    {
        switch ($type)
        {
            case "cps" :
                return $this->processCPS($data);
            case "rpps" :
                return $this->processRPPS($data);
        }

        throw new \Exception("Type $type is not supported yet");

    }

    /**
     * @param array $data
     *
     * @return RPPS|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function processCPS(array $data): ?RPPS
    {

        /** @var RPPS $rpps */
        $rpps = $this->repository->find($data[1]);

        if (null === $rpps) {
            return null;
        }

        $rpps->setCpsNumber($data[11]);

        return $rpps;
    }


    /**
     * @param array $data
     *
     * @return RPPS|null
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function processRPPS(array $data): ?RPPS
    {

        /** @var RPPS|null $rpps */
        $rpps = $this->repository->find($data[2]);

        if (null === $rpps) {
            $rpps = new RPPS();
        }

        $rpps->setIdRpps($data[2]);
        $rpps->setTitle($data[4]);
        $rpps->setLastName($data[5]);
        $rpps->setFirstName($data[6]);
        $rpps->setSpecialty($data[8]);

        if($data[12] && $data[13] == "S") {
            $rpps->setSpecialty($data[12]);
        }

        $rpps->setAddress($data[24] . " " . $data[25] . " " . $data[27] . " " . $data[28] . " " . $data[29]);
        $rpps->setZipcode($data[31]);
        $rpps->setCity($data[30]);
        $rpps->setPhoneNumber(str_replace(' ', '', $data[36]));
        $rpps->setEmail($data[39]);
        $rpps->setFinessNumber($data[18]);

        return $rpps;
    }

}
