<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 26/09/2018
 * Time: 16:46
 */

namespace Z1lab\JsonApi\Repositories;


interface RepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param string $id
     * @param array  $data
     * @return mixed
     */
    public function update(array $data, string $id);

    /**
     * @param string $id
     * @return mixed
     */
    public function destroy(string $id);

    /**
     * @param string $id
     * @param array  $with
     * @return mixed
     */
    public function find(string $id, array $with = []);

    /**
     * @param int $items
     * @return mixed
     */
    public function list(int $items = 20);
}
