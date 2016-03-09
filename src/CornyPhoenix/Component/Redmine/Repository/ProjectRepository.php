<?php

namespace CornyPhoenix\Component\Redmine\Repository;

use CornyPhoenix\Component\Redmine\Model\Project;

class ProjectRepository extends AbstractRepository
{

    /**
     * @param int $id
     * @return null|Project
     */
    public function find($id)
    {
        $query = $this->client->project->show($id);

        if (false === $query || !isset($query['project'])) {
            return null;
        }

        return $this->serializer->denormalize($query['project'], Project::class);
    }
}