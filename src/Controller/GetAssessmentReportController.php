<?php

namespace JoJoBizzareCoders\DigitalJournal\Controller;

final class GetAssessmentReportController extends GetAssessmentReportCollectionController
{
    /**
     * @inheritDoc
     */
    protected function buildHttpCode(array $foundReport): int
    {
        return 0 === count($foundReport) ? 404 : 200;
    }

    /**
     * @inheritDoc
     */
    protected function buildResult(array $foundReport)
    {
        return 1 === count($foundReport) ? $this->serializeReport(current($foundReport)) : [
            'status' => 'fail',
            'message' => 'Entity not found'
        ];
    }
}