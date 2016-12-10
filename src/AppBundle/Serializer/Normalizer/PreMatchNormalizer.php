<?php

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\PreMatch;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PreMatchNormalizer implements NormalizerInterface
{
    /**
     * {@inheritdoc}
     *
     * @param PreMatch $object
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $data = [
            'id' => $object->getId(),
            'learner' => [
                'request_created_at' => $object->getLearnerConnectionRequest()->getCreatedAt()->format('Y-m-d'),
                'name' => $object->getLearnerConnectionRequest()->getUser()->getName(),
                'email' => $object->getLearnerConnectionRequest()->getUser()->getEmail(),
                'user_id' => $object->getLearnerConnectionRequest()->getUser()->getId(),
            ],
        ];

        if ($object->getFluentSpeakerConnectionRequest()) {
            $data['fluent_speaker'] = [
                'request_created_at' => $object->getFluentSpeakerConnectionRequest()->getCreatedAt()->format('Y-m-d'),
                'name' => $object->getFluentSpeakerConnectionRequest()->getUser()->getName(),
                'email' => $object->getFluentSpeakerConnectionRequest()->getUser()->getEmail(),
                'user_id' => $object->getFluentSpeakerConnectionRequest()->getUser()->getId(),
            ];
        } else {
            $data['fluent_speaker'] = null;
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return (is_object($data) && ClassUtils::getRealClass(get_class($data)) == PreMatch::class);
    }
}
