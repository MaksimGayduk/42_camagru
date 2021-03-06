<?php


namespace app\widgets\inputForm\components;


use app\base\Widget;

abstract class Input extends Widget
{
    /** @var string  */
    protected $name;

    protected $value;

    /** @var null|string  */
    protected $auxValue;

    /** @var bool  */
    protected $unique;

    /** @var string */
    protected $id;

    /** @var string[] */
    protected $checks;

    /** @var bool  */
    protected $fillValue = true;

    protected $save = true;

    public function __construct(array $params = [], bool $async = false)
    {
        parent::__construct($params, $async);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null|string
     */
    public function getAuxValue(): ?string
    {
        return $this->auxValue;
    }

    /**
     * @param null|string $auxValue
     */
    public function setAuxValue(?string $auxValue): void
    {
        $this->auxValue = $auxValue;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): void
    {
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function getChecks(): ?array
    {
        return $this->checks;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id ?: '';
    }

    /**
     * @return bool
     */
    public function isUnique(): bool
    {
        return (bool)$this->unique;
    }

    /**
     * @return bool
     */
    public function shouldFeel(): bool
    {
        return $this->fillValue;
    }

    public function shouldSave(): bool
    {
        return $this->save;
    }
}
