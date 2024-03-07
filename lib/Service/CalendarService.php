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
use DateTime;

use OCA\Period\Db\CalendarMapper;
use OCA\Period\Db\Calendar;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

use OCP\ILogger;

class CalendarService {
    private CalendarMapper $mapper;
    private ILogger $logger;

    public function __construct(CalendarMapper $mapper, ILogger $logger) {
        $this->mapper = $mapper;
        $this->logger = $logger;
    }

    /**
     * @return list<Calendar>
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

    public function find(int $calendarId, string $userId): Calendar {
        try {
            return $this->mapper->find($calendarId, $userId);

            // in order to be able to plug in different storage backends like files
            // for instance it is a good idea to turn storage related exceptions
            // into service related exceptions so controllers and service users
            // have to deal with only one type of exception
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function create(?DateTime $date, string $note,
                           string $symptomId, string $userId): Calendar {
        $calendar = new Calendar();
        $calendar->setDate($date);
        $calendar->setNote($note);
        $calendar->setSymptomId($symptomId);
        $calendar->setUserId($userId);
        return $this->mapper->insert($calendar);
    }

    public function update(int $calendarId, ?DateTime $date, string $note,
                           string $symptomId, string $userId): Calendar {
        try {
            $calendar = $this->mapper->find($calendarId, $userId);
            $calendar->setDate($date);
            $calendar->setNote($note);
            $calendar->setSymptomId($symptomId);
            return $this->mapper->update($calendar);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    public function delete(int $calendarId, string $userId): Calendar {
        $this->logger->debug('Trying to delete calendar id: '.$calendarId);
        $this->logger->debug('by user id: '.$userId);
        try {
            $calendar = $this->mapper->find($calendarId, $userId);
            $this->mapper->delete($calendar);
            return $calendar;
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }
}
