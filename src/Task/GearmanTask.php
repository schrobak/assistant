<?php

namespace Revolve\Assistant\Task;

use GearmanJob;
use Revolve\Assistant\Messenger\MessengerInterface;

class GearmanTask extends Task
{
    /**
     * @var GearmanJob
     */
    protected $job;

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
     * @return GearmanJob
     */
    public function getJob()
    {
        return $this->job;
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

    /**
     * @param MessengerInterface $messenger
     * @param array              $parameters
     *
     * @return $this
     */
    public function write(MessengerInterface $messenger, array $parameters = [])
    {
        // TODO: Implement write() method.
    }
}
