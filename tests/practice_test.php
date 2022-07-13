<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/*
 * Testing helper class methods in payments API.
 *
 * @category   test
 *
 * @copyright  2020 Marina Glancy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use advanced_testcase;
namespace quizaccess_proctoring;

/**
 * Testing helper class methods in payments API.
 *
 * @category   test
 *
 * @copyright  2020 Marina Glancy
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class practice_test extends advanced_testcase {
    public function test_empty(): array {
        $stack = [];
        $this->assertEmpty($stack);

        return $stack;
    }

    /**
     * @depends test_empty
     */
    public function test_push(array $stack): array {
        array_push($stack, 'foo');
        $this->assertSame('foo', $stack[count($stack) - 1]);
        $this->assertNotEmpty($stack);

        return $stack;
    }

    /**
     * @depends test_push
     */
    public function test_pop(array $stack): void {
        $this->assertSame('foo', array_pop($stack));
        $this->assertEmpty($stack);
    }
}
