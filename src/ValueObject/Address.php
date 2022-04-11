<?php

    namespace JoJoBizzareCoders\DigitalJournal\ValueObject;

    use Doctrine\ORM\Mapping as ORM;

    /**
     * Адресс пользователя
     *
     *
     * @ORM\Embeddable
     */
class Address
{

    /**
     * Улица пользователя
     *
     * @var string
     * @ORM\Column(name="street", type="string", length=250, nullable=false)
     */
    private string $street;

    /**
     * Номер дома пользователя
     *
     * @var string
     * @ORM\Column(name="home", type="string", length=250, nullable=false)
     */
    private string $home;

    /**
     * Номер квартиры пользователя
     *
     * @var string
     * @ORM\Column(name="apartment", type="string", length=250, nullable=false)
     */
    private string $apartment;


    /**
     * Конструктор адресса пользователя
     *
     * @param string $street - улица пользователя
     * @param string $home - номер дома пользователя
     * @param string $apartment - номер квартиры пользователя
     */
    public function __construct(string $street, string $home, string $apartment)
    {
        $this->street = $street;
        $this->home = $home;
        $this->apartment = $apartment;
    }

    /**
     * возвращает улицу пользователя
     *
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * возвращает номер дома пользователя
     *
     * @return string
     */
    public function getHome(): string
    {
        return $this->home;
    }

    /**
     * возвращает номер квартиры пользователя
     *
     * @return string
     */
    public function getApartment(): string
    {
        return $this->apartment;
    }
}
