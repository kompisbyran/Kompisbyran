<?php

namespace AppkBundle\Tests\Manager;

use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\ConnectionRequestRepository;
use AppBundle\Entity\PreMatch;
use AppBundle\Entity\UserRepository;
use AppBundle\Manager\PreMatchManager;
use Doctrine\ORM\EntityManager;

class PreMatchManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider meetingTimeProvider
     */
    public function shouldReturnMeetingTime(
        $expected,
        $date,
        $learnerWeekday,
        $learnerWeekend,
        $learnerDaytime,
        $learnerEvening,
        $fluentSpeakerWeekday,
        $fluentSpeakerWeekend,
        $fluentSpeakerDaytime,
        $fluentSpeakerEvening
    )
    {
        $preMatchManager = new PreMatchManager(
            $this->getMockBuilder(ConnectionRequestRepository::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock(),
            $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock(),
            new \DateTime($date)
        );

        $learnerConnectionRequestMock = $this->getMock(ConnectionRequest::class);
        $learnerConnectionRequestMock->method('isAvailableWeekday')->willReturn($learnerWeekday);
        $learnerConnectionRequestMock->method('isAvailableWeekend')->willReturn($learnerWeekend);
        $learnerConnectionRequestMock->method('isAvailableDay')->willReturn($learnerDaytime);
        $learnerConnectionRequestMock->method('isAvailableEvening')->willReturn($learnerEvening);

        $fluentSpeakerConnectionRequestMock = $this->getMock(ConnectionRequest::class);
        $fluentSpeakerConnectionRequestMock->method('isAvailableWeekday')->willReturn($fluentSpeakerWeekday);
        $fluentSpeakerConnectionRequestMock->method('isAvailableWeekend')->willReturn($fluentSpeakerWeekend);
        $fluentSpeakerConnectionRequestMock->method('isAvailableDay')->willReturn($fluentSpeakerDaytime);
        $fluentSpeakerConnectionRequestMock->method('isAvailableEvening')->willReturn($fluentSpeakerEvening);

        $preMatchMock = $this->getMock(PreMatch::class);
        $preMatchMock->method('getLearnerConnectionRequest')->willReturn($learnerConnectionRequestMock);
        $preMatchMock->method('getFluentSpeakerConnectionRequest')->willReturn($fluentSpeakerConnectionRequestMock);

        $this->assertEquals($expected, $preMatchManager->getMeetingTime($preMatchMock));
    }

    /**
     * @return array
     */
    public function meetingTimeProvider()
    {
        return [
            ['Måndag 2017-01-23 klockan 12', '2017-01-15', true, false, true, false, true, false, true, false], // Weekday, daytime
            ['Måndag 2017-01-23 klockan 12', '2017-01-16', true, false, true, false, true, false, true, false], // Weekday, daytime
            ['Tisdag 2017-01-24 klockan 12', '2017-01-17', true, false, true, false, true, false, true, false], // Weekday, daytime
            ['Måndag 2017-01-23 klockan 12', '2017-01-15', true, false, true, true, true, false, true, true], // Weekday, daytime, evening
            ['Måndag 2017-01-23 klockan 12', '2017-01-16', true, false, true, true, true, false, true, true], // Weekday, daytime, evening
            ['Tisdag 2017-01-24 klockan 12', '2017-01-17', true, false, true, true, true, false, true, true], // Weekday, daytime, evening
            ['Måndag 2017-01-23 klockan 18', '2017-01-15', true, false, false, true, true, false, false, true], // Weekday, evening
            ['Måndag 2017-01-23 klockan 18', '2017-01-16', true, false, false, true, true, false, false, true], // Weekday, evening
            ['Tisdag 2017-01-24 klockan 18', '2017-01-17', true, false, false, true, true, false, false, true], // Weekday, evening

            ['Söndag 2017-01-22 klockan 18', '2017-01-15', false, true, false, true, false, true, false, true], // Weekend, evening
            ['Lördag 2017-01-28 klockan 18', '2017-01-16', false, true, false, true, false, true, false, true], // Weekend, evening
            ['Lördag 2017-01-28 klockan 18', '2017-01-17', false, true, false, true, false, true, false, true], // Weekend, evening

            ['Söndag 2017-01-22', '2017-01-15', false, false, false, false, false, false, false, false], 
        ];
    }
}
