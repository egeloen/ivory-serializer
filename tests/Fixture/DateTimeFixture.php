<?php

/*
 * This file is part of the Ivory Serializer package.
 *
 * (c) Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Ivory\Tests\Serializer\Fixture;

use Ivory\Serializer\Mapping\Annotation as Serializer;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class DateTimeFixture implements FixtureInterface
{
    /**
     * @Serializer\Type("DateTime")
     *
     * @var \DateTime
     */
    public $dateTime;

    /**
     * @Serializer\Type("DateTime<format='Y-m-d, H:i:s, P'>")
     *
     * @var \DateTime
     */
    public $formattedDateTime;

    /**
     * @Serializer\Type("DateTime<timezone='Europe/Paris'>")
     *
     * @var \DateTime
     */
    public $timeZonedDateTime;

    /**
     * @Serializer\Type("DateTimeImmutable")
     *
     * @var \DateTimeImmutable
     */
    public $immutableDateTime;

    /**
     * @Serializer\Type("DateTimeImmutable<format='Y-m-d, H:i:s, P'>")
     *
     * @var \DateTimeImmutable
     */
    public $formattedImmutableDateTime;

    /**
     * @Serializer\Type("DateTimeImmutable<timezone='Europe/Paris'>")
     *
     * @var \DateTimeImmutable
     */
    public $timeZonedImmutableDateTime;

    /**
     * {@inheritdoc}
     */
    public function toArray(array $options = [])
    {
        $format = \DateTime::RFC3339;

        return [
            'dateTime'                   => $this->dateTime !== null ? $this->dateTime->format($format) : null,
            'formattedDateTime'          => $this->formattedDateTime !== null ? $this->formattedDateTime->format($format) : null,
            'timeZonedDateTime'          => $this->timeZonedDateTime !== null ? $this->timeZonedDateTime->format($format) : null,
            'immutableDateTime'          => $this->immutableDateTime !== null ? $this->immutableDateTime->format($format) : null,
            'immutableFormattedDateTime' => $this->formattedImmutableDateTime !== null ? $this->formattedImmutableDateTime->format($format) : null,
            'immutableTimeZonedDateTime' => $this->timeZonedImmutableDateTime !== null ? $this->timeZonedImmutableDateTime->format($format) : null,
        ];
    }
}
