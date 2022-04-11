<?php

    namespace JoJoBizzareCoders\DigitalJournal\Controller;

final class GetLessonController extends GetLessonCollectionController
{
    /**
     * @inheritDoc
     */
    protected function buildHttpCode(array $foundLesson): int
    {
        return 0 === count($foundLesson) ? 404 : 200;
    }

    /**
     * @inheritDoc
     */
    protected function buildResult(array $foundLesson)
    {
        return 1 === count($foundLesson) ? $this->serializeLesson(current($foundLesson)) : [
            'status' => 'fail',
            'message' => 'Entity not found'
        ];
    }
}
