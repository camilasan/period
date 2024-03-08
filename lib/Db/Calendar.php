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

namespace OCA\Period\Db;

use JsonSerializable;
use DateTime;

use OCP\AppFramework\Db\Entity;

/**
 * @method getId(): int
 * @method getFeeling(): string
 * @method setFeeling(string $feeling): void
 * @method getDate(): ?DateTime
 * @method setDate(?DateTime $date): void
 * @method getNote(): string
 * @method setNote(string $note): void
 * @method getSymptomId(): string
 * @method setSymptomId(string $symptomId): void
 * @method getContraceptiveId(): string
 * @method setContraceptiveId(string $contraceptiveId): void
 * @method getUserId(): string
 * @method setUserId(string $userId): void
 */

class Calendar extends Entity implements JsonSerializable {
    public int $id;
    protected ?DateTime $date = null;
    protected string $feeling = '';
    protected string $note = '';
    protected string $symptomId = '';
    protected string $contraceptiveId = '';
    protected string $userId = '';

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'feeling' => $this->feeling,
            'date' => $this->date,
            'note' => $this->note,
            'symptomId' => $this->symptomId,
            'contraceptiveId' => $this->contraceptiveId,
            'userId' => $this->userId,
        ];
    }
}
