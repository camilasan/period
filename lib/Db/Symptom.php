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
 * @method getId(): int
 * @method getDescription(): string
 * @method setDescription(string $description): void
 * @method getCategoryId(): string
 * @method setCategoryId(string $categoryId): void
 * @method getUserId(): string
 * @method setUserId(string $userId): void
 */
class Symptom extends Entity implements JsonSerializable {
    public int $id;
    protected string $description = '';
    protected string $categoryId = '';
    protected string $userId = '';

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'categoryId' => $this->categoryId,
            'userId' => $this->userId,
        ];
    }
}

