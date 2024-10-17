<?php
namespace App\Model;

class ZbInstrument{

    private int $id;
    private string $name;
    private string $code;
    private ?string $graph_link;
    private ?string $link;
    private string $exchange_place;

    /**
     * Get the value of id
     */ 
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of code
     */ 
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set the value of code
     *
     * @return  self
     */ 
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get the value of graph_link
     */ 
    public function getGraph_link(): ?string
    {
        return $this->graph_link;
    }

    /**
     * Set the value of graph_link
     *
     * @return  self
     */ 
    public function setGraph_link(?string $graph_link): self
    {
        $this->graph_link = $graph_link;

        return $this;
    }

    /**
     * Get the value of exchange_place
     */ 
    public function getExchange_place()
    {
        return $this->exchange_place;
    }

    /**
     * Set the value of exchange_place
     *
     * @return  self
     */ 
    public function setExchange_place($exchange_place)
    {
        $this->exchange_place = $exchange_place;

        return $this;
    }

        /**
     * Get the value of link
     */ 
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * Set the value of link
     *
     * @return  self
     */ 
    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function __toArray(): array
    {
        return get_object_vars($this);
    }

    public function convertToObject(array $zbInstrumentArray): self
    {
        $this->setId($zbInstrumentArray["id"]);
        $this->setName($zbInstrumentArray["name"]);
        $this->setCode($zbInstrumentArray["code"]);
        $this->setGraph_link($zbInstrumentArray["graph_link"]);
        $this->setLink($zbInstrumentArray["link"]);
        $this->setExchange_place($zbInstrumentArray["exchange_place"]);
        return $this;
    }
}