<?php

namespace App\Core\Dto\HttpClientResponse;

class FetchUserDetailInfoDto
{
    public function __construct(
        protected array   $response,
        protected ?string $birth_date = null,
        protected ?string $passport_no = null,
        protected ?string $pin_fl = null,
        protected ?string $email = null,
        protected ?string $first_name = null,
        protected ?string $last_name = null,
        protected ?string $middle_name = null,
        protected ?string $username = null,
        protected ?int    $gender = null,
        protected ?string $current_address = null,
        protected ?string $stir = null,
        protected ?string $authority_name = null
    )
    {
        foreach ($this->response as $key => $value) {
            if ($key === 'sur_name') {
                $this->setLastName($value);
            } elseif ($key === 'pport_no') {
                $this->setPassportNo($value);
            } elseif ($key === 'pin') {
                $this->setPinFl($value);
            } elseif ($key === 'user_id') {
                $this->setUsername($value);
            } elseif ($key === 'mid_name') {
                $this->setMiddleName($value);
            } elseif ($key === 'gd') {
                $this->setGender($value);
            } elseif ($key === 'legal_info') {
                if (!empty($value['legal_info'][0]['tin'])) {
                    $this->setStir($value['legal_info'][0]['tin']);
                }
                if (!empty($value['legal_info'][0]['le_name'])) {
                    $this->setAuthorityName($value['legal_info'][0]['le_name']);
                }
            } elseif ($key === 'per_address') {
                $this->setCurrentAddress($value);
            } else {
                if (property_exists(self::class, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public function getCurrentAddress(): ?string
    {
        return $this->current_address;
    }

    public function setCurrentAddress(?string $current_address): void
    {
        $this->current_address = $current_address;
    }

    public function getGender(): ?int
    {
        return $this->gender;
    }

    public function setGender(?int $gender): void
    {
        $this->gender = $gender;
    }

    public function getBirthDate(): ?string
    {
        return $this->birth_date;
    }

    public function setBirthDate(?string $birth_date): void
    {
        $this->birth_date = $birth_date;
    }

    public function getPassportNo(): ?string
    {
        return $this->passport_no;
    }

    public function setPassportNo(?string $passport_no): void
    {
        $this->passport_no = $passport_no;
    }

    public function getPinFl(): ?string
    {
        return $this->pin_fl;
    }

    public function getStir(): ?string
    {
        return $this->stir;
    }

    public function setPinFl(?string $pin_fl): void
    {
        $this->pin_fl = $pin_fl;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getMiddleName(): ?string
    {
        return $this->middle_name;
    }

    public function setMiddleName(?string $middle_name): void
    {
        $this->middle_name = $middle_name;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function setAuthorityName(?string $name): void
    {
        $this->authority_name = $name;
    }

    public function setStir(?string $stir): void
    {
        $this->stir = $stir;
    }

    /**
     * @return bool
     */
    public function isEmptyPinFl(): bool
    {
        return empty($this->getPinFl());
    }

    /**
     * @return bool
     */
    public function isEmptyStir(): bool
    {
        return empty($this->getStir());
    }
}
