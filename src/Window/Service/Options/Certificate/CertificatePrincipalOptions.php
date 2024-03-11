<?php

namespace iflow\native\Window\Service\Options\Certificate;

use iflow\native\Window\Service\Options\Options;

class CertificatePrincipalOptions extends Options {

    /**
     * 通用名
     * @var string
     */
    protected string $commonName;

    /**
     * 组织名称
     * @var string[]
     */
    protected array $organizations;

    /**
     * 组织单位名称
     * @var string[]
     */
    protected array $organizationUnits;

    /**
     * 地区
     * @var string
     */
    protected string $locality;

    /**
     * 州或省
     * @var string
     */
    protected string $state;

    /**
     * 国家或地区
     * @var string
     */
    protected string $country;
}
