<?php

namespace App\Repository;

use App\Entity\Vehicule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Exception;

/**
 * @extends ServiceEntityRepository<Vehicule>
 *
 * @method Vehicule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicule[]    findAll()
 * @method Vehicule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehiculeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicule::class);
    }

    public function add(Vehicule $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Vehicule $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllVehiculeFreeQuery($date_deb, $date_fin){
        $expr = $this->getEntityManager()->getExpressionBuilder();

        $vehiculeReserver = $this->createQueryBuilder('v')
            ->select('v.id')
            ->leftJoin('v.commandes', 'c')
            ->andWhere($expr->andX(
                'c.date_heur_depart <= :dateFin',
                'c.date_heur_fin >= :dateDebut'
            ))
            ->orWhere($expr->andX(
                'c.date_heur_depart >= :dateDebut',
                'c.date_heur_fin <= :dateFin'
            ))
            ->orWhere($expr->andX(
                'c.date_heur_depart <= :dateDebut',
                'c.date_heur_fin >= :dateFin'
            ))
            ->setParameter('dateDebut', $date_deb)
            ->setParameter('dateFin', $date_fin)
            ->distinct()
            ->getQuery()
            ->getResult();

        $queryBuilder = $this->createQueryBuilder('v');
        if($vehiculeReserver == []){
            return [$queryBuilder, [['id' => 0]]];
        }

        $queryBuilder
            ->andWhere('v.id NOT IN (:reserver)')
            ->setParameter('reserver', $vehiculeReserver);

        return [$queryBuilder, $vehiculeReserver];
    }

    public function findAllVehiculeFree($date_deb, $date_fin){
        $queryBuilder = $this->findAllVehiculeFreeQuery($date_deb, $date_fin)[0];

        return $queryBuilder->getQuery()->getResult();
    }

    public function findVehiculeByDatesAndFiltre($data, $date_deb, $date_fin){
        $queryBuilder = $this->findAllVehiculeFreeQuery($date_deb, $date_fin);
        $vehiculeReserver = $queryBuilder[1];
        $queryBuilder = $queryBuilder[0];
        $query = $queryBuilder->getQuery();
        $sql = $query->getSQL();
        foreach ($data as $key => $value){
            if(str_starts_with($key, 'order')){
                $key = str_replace('order', '', $key);
                $sql .= ' ORDER BY v0_.'.$key.' '.$value;
            } else if($value != null){
                if(!str_contains($sql, '?') and !str_contains($sql, 'WHERE')){
                    $sql .= ' WHERE';
                }else{
                    $sql .= ' AND';
                }
                $sql .= ' v0_.'.$key.' LIKE '.$value;
            }
        }
        $stringVehiculeReserver = '';
        foreach ($vehiculeReserver as $vehicule){
            $stringVehiculeReserver .= $vehicule['id'].', ';
        }
        $stringVehiculeReserver = substr($stringVehiculeReserver, 0, -2);
        $sql = str_replace('vehicule', 'App\Entity\Vehicule', $sql);
        $sql = str_replace('?', $stringVehiculeReserver, $sql);
        try {
            $results = $this->getEntityManager()->createQuery($sql)->getResult();
        } catch (\Exception $e) {
            $results = [];
        }
        $vehicules = [];
        foreach ($results as $result) {
            $vehicules[] = $this->getEntityManager()->getRepository('App\Entity\Vehicule')->find($result['id_0']);
        }
        return $vehicules;
    }

}