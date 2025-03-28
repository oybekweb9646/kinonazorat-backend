<?php

namespace App\Core\Dto\HttpClientResponse;

class FetchAuthorityDto
{
    public function __construct(
        protected ?array $response,
        public ?string  $name = null {
            get {
                return $this->name;

            }
        },
        public ?int     $stir = null {
            get {
                return $this->stir;
            }
        },
        public ?string  $billing_address = null {
            get {
                return $this->billing_address;
            }
        },
        public ?string  $director_address = null {
            get {
                return $this->director_address;
            }
        },
        public ?int     $director_soato = null {
            get {
                return $this->director_soato;
            }
        },
        public ?int     $billing_soato = null {
            get {
                return $this->billing_soato;
            }
        },
        public ?string  $director_lastName = null {
            get {
                return $this->director_lastName;
            }
        },
        public ?string  $director_firstName = null {
            get {
                return $this->director_firstName;
            }
        },
        public ?string  $director_middleName = null {
            get {
                return $this->director_middleName;
            }
        },
        public ?int     $director_gender = null {
            get {
                return $this->director_gender;
            }
        },
        public ?int     $director_nationality = null {
            get {
                return $this->director_nationality;
            }
        },
        public ?int     $director_citizenship = null {
            get {
                return $this->director_citizenship;
            }
        },
        public ?int     $director_passportNumber = null {
            get {
                return $this->director_passportNumber;
            }
        },
        public ?int     $director_stir = null {
            get {
                return $this->director_stir;
            }
        },
        public ?int     $director_pinfl = null {
            get {
                return $this->director_pinfl;
            }
        },
        public ?int     $director_countryCode = null {
            get {
                return $this->director_countryCode;
            }
        },
        public ?string     $director_passportSeries = null {
            get {
                return $this->director_passportSeries;
            }
        },
    )
    {
        $this->stir = data_get($this->response, 'company.tin');
        $this->name = data_get($this->response, 'company.name');
        $this->billing_address = data_get($this->response, 'companyBillingAddress.streetName');
        $this->billing_soato = data_get($this->response, 'companyBillingAddress.soato');
        $this->director_address = data_get($this->response, 'directorAddress.streetName');
        $this->director_soato = data_get($this->response, 'directorAddress.soato');
        $this->director_lastName = data_get($this->response, 'director.lastName');
        $this->director_middleName = data_get($this->response, 'director.middleName');
        $this->director_firstName = data_get($this->response, 'director.firstName');
        $this->director_gender = data_get($this->response, 'director.gender');
        $this->director_nationality = data_get($this->response, 'director.nationality');
        $this->director_citizenship = data_get($this->response, 'director.citizenship');
        $this->director_passportNumber = data_get($this->response, 'director.passportNumber');
        $this->director_stir = data_get($this->response, 'director.tin');
        $this->director_pinfl = data_get($this->response, 'director.pinfl');
        $this->director_countryCode = data_get($this->response, 'director.countryCode');
        $this->director_passportSeries = data_get($this->response, 'director.passportSeries');
    }

}
