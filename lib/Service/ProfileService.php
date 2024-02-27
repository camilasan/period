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

use OCA\Period\Db\Profile;
use OCA\Period\Db\ProfileMapper;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

use OCP\ILogger;

class ProfileService {
    private ProfileMapper $mapper;
    private ILogger $logger;

    public function __construct(ProfileMapper $mapper, ILogger $logger) {
        $this->mapper = $mapper;
        $this->logger = $logger;
    }

    /**
     * @return list<Profile>
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

    public function find(int $profileId, string $userId): Profile {
        try {
            return $this->mapper->find($profileId, $userId);

            // in order to be able to plug in different storage backends like files
            // for instance it is a good idea to turn storage related exceptions
            // into service related exceptions so controllers and service users
            // have to deal with only one type of exception
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function create(string $name, int $age, string $contraceptive, string $userId): Profile {
        $profile = new Profile();
        $profile->setName($name);
        $profile->setAge($age);
        $profile->setContraceptive($contraceptive);
        $profile->setUserId($userId);
        return $this->mapper->insert($profile);
    }

    public function update(int $profileId, string $name, int $age, string $contraceptive, string $userId): Profile {
        try {
            $profile = $this->mapper->find($profileId, $userId);
            $profile->setName($name);
            $profile->setAge($age);
            $profile->setContraceptive($contraceptive);
            return $this->mapper->update($profile);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function delete(int $profileId, string $userId): Profile {
        $this->logger->debug('Trying to delete profile id: '.$profileId);
        $this->logger->debug('by user id: '.$userId);
        try {
            $profile = $this->mapper->find($profileId, $userId);
            $this->mapper->delete($profile);
            return $profile;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
}
