<?php
declare(strict_types=1);

namespace Zero0719\HyperfAdmin\Service;

use Hyperf\Database\Model\Builder;
use Hyperf\HttpServer\Contract\RequestInterface;

class BaseService
{
    /**
     * @var RequestInterface
     */
    protected $request;
    
    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }
    
    protected function getForPage(Builder $builder)
    {
        $page = $this->request->input('page', 1);
        $pageSize = $this->request->input('pageSize', 15);
        $offset = ($page - 1) * $pageSize;
        $builder->limit($pageSize);
        $builder->offset($offset);
        return $builder->get();
    }
}