<?php

namespace TestMonitor\DoneDone\Responses;

class PaginatedResponse
{
    /**
     * All of the items being paginated.
     *
     * @var array
     */
    protected array $items;

    /**
     * The total number of items before slicing.
     *
     * @var int
     */
    protected int $total;

    /**
     * The number of items to be shown per page.
     *
     * @var int
     */
    protected int $perPage;

    /**
     * The current page.
     *
     * @var int
     */
    protected int $currentPage;

    /**
     * Create a new paginated response instance.
     *
     * @param array $items
     * @param int $total
     * @param int $perPage
     * @param int $currentPage
     */
    public function __construct(array $items, int $total, int $perPage, int $currentPage = 1)
    {
        $this->items = $items;
        $this->total = $total;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;
    }

    /**
     * Get the items being paginated.
     *
     * @return array
     */
    public function items(): array
    {
        return $this->items;
    }

    /**
     * Get the total number of items being paginated.
     *
     * @return int
     */
    public function total(): int
    {
        return $this->total;
    }

    /**
     * Get the number of items shown per page.
     *
     * @return int
     */
    public function perPage(): int
    {
        return $this->perPage;
    }

    /**
     * Get the current page.
     *
     * @return int
     */
    public function currentPage(): int
    {
        return $this->currentPage;
    }
}
