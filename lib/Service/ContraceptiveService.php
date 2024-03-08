<?php
/**
 * @copyright Copyright (c) 2024 Camila Ayres <hello@camilasan.com>
 *
 * @author Camila Ayres <hello@camilasan.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

declare(strict_types=1);

namespace OCA\Period\Service;

use Exception;

use OCA\Period\Db\ContraceptiveMapper;
use OCA\Period\Db\Contraceptive;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

use OCP\ILogger;

class ContraceptiveService {
    private ContraceptiveMapper $mapper;
    private ILogger $logger;

    public function __construct(ContraceptiveMapper $mapper, ILogger $logger) {
        $this->mapper = $mapper;
        $this->logger = $logger;
    }

    /**
     * @return list<Contraceptive>
     */
    public function findAll(string $userId): array {
        return $this->mapper->findAll($userId);
    }

    /**
     * @return never
     * @throws ExceptionNotFound
     * @throws Exception
     */
    private function handleException(Exception $e) {
        if ($e instanceof DoesNotExistException ||
            $e instanceof MultipleObjectsReturnedException) {
            throw new ExceptionNotFound($e->getMessage());
        } else {
            throw $e;
        }
    }

    public function find(int $contraceptiveId, string $userId): Contraceptive {
        try {
            return $this->mapper->find($contraceptiveId, $userId);

            // in order to be able to plug in different storage backends like files
            // for instance it is a good idea to turn storage related exceptions
            // into service related exceptions so controllers and service users
            // have to deal with only one type of exception
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function create(string $name, string $description,
                           string $sideEffects, string $userId): Contraceptive {
        $contraceptive = new Contraceptive();
        $contraceptive->setName($name);
        $contraceptive->setDescription($description);
        $contraceptive->setSideEffects($sideEffects);
        $contraceptive->setUserId($userId);
        return $this->mapper->insert($contraceptive);
    }

    public function update(int $contraceptiveId, string $name, string $description,
                           string $sideEffects, string $userId): Contraceptive {
        try {
            $contraceptive = $this->mapper->find($contraceptiveId, $userId);
            $contraceptive->setName($name);
            $contraceptive->setDescription($description);
            $contraceptive->setSideEffects($sideEffects);
            return $this->mapper->update($contraceptive);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function delete(int $contraceptiveId, string $userId): Contraceptive {
        $this->logger->debug('Trying to delete contraceptive id: ' . $contraceptiveId);
        $this->logger->debug('by user id: '.$userId);
        try {
            $contraceptive = $this->mapper->find($contraceptiveId, $userId);
            $this->mapper->delete($contraceptive);
            return $contraceptive;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
}
