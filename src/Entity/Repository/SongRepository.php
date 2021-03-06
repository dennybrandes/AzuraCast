<?php
namespace App\Entity\Repository;

use App\Entity;
use Azura\Doctrine\Repository;

class SongRepository extends Repository
{
    /**
     * Get a list of all song IDs.
     *
     * @return array
     */
    public function getIds()
    {
        $ids_raw = $this->_em->createQuery(/** @lang DQL */ 'SELECT s.id FROM App\Entity\Song s')
            ->getArrayResult();

        $ids = [];
        foreach ($ids_raw as $id_raw) {
            $ids[] = $id_raw['id'];
        }
        return $ids;
    }

    /**
     * Retrieve an existing Song entity or create a new one.
     *
     * @param array|string $song_info
     * @param bool $is_radio_play
     *
     * @return Entity\Song
     */
    public function getOrCreate($song_info, $is_radio_play = false): Entity\Song
    {
        if (!is_array($song_info)) {
            $song_info = ['text' => $song_info];
        }

        $song_hash = Entity\Song::getSongHash($song_info);

        $obj = $this->find($song_hash);

        if (!($obj instanceof Entity\Song)) {
            $obj = new Entity\Song($song_info);
        }

        if ($is_radio_play) {
            $obj->played();
        }

        $this->_em->persist($obj);
        $this->_em->flush($obj);

        return $obj;
    }
}
