<?php

namespace Revolve\Assistant\Provider\Gearman;

use GearmanJob;
use Revolve\Assistant\Messenger\MessengerInterface;
use Revolve\Assistant\Task\Task as AbstractTask;

class Task extends AbstractTask
{
    /**
     * @var GearmanJob
     */
    protected $job;

    /**
     * @return GearmanJob
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param GearmanJob $job
     *
     * @return $this
     */
    public function setJob(GearmanJob $job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * @param MessengerInterface $messenger
     *
     * @return $this
     */
    public function writeTo(MessengerInterface $messenger)
    {
        $parameters = array_slice(func_get_args(), 1);

        array_unshift($parameters, $this->getId());

        $messenger->write(serialize($parameters));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        if ($this->job) {
            return $this->job->handle();
        }

        return parent::getId();
    }
}
