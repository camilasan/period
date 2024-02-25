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

use OCP\AppFramework\Db\Entity;

/**
 * @method getProfileId(): int
 * @method getName(): string
 * @method setName(string $name): void
 * @method getAge(): int
 * @method setAge(int $age): void
 * @method getContraceptive(): string
 * @method setContraceptive(string $contraceptive): void
 * @method getUserId(): string
 * @method setUserId(string $userId): void
 */
class Profile extends Entity implements JsonSerializable {
    protected string $name = '';
    protected int $age = 0;
    protected string $contraceptive = '';
    protected string $userId = '';

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->age,
            'contraceptive' => $this->contraceptive
        ];
    }
}
