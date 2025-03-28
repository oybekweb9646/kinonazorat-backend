<?php

namespace App\Core\Dto\Auth;

class AccessAuthDto
{
    /**
     * @param array $response
     * @param \stdClass|null $stdClass
     * @param string|null $priv
     */
    public function __construct(
        protected array      $response,
        protected ?\stdClass $stdClass = null,
        protected ?string    $priv = null,
    )
    {
        foreach ($this->response as $key => $object) {
            if ($object instanceof \stdClass) {
                $this->priv = $object->priv;
            } else if (is_array($object)) {
                $this->priv = $object['priv'];
            } else {
                abort(400, __('client.Error response structure. Uzb api api/v3/privs'));
            }
        }
    }

    public function getPriv(): ?string
    {
        return $this->priv;
    }

    public function setPriv(?string $priv): void
    {
        $this->priv = $priv;
    }
}
