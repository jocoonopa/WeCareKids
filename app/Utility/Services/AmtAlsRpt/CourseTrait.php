<?php

namespace App\Utility\Services\AmtAlsRpt;

use App\Model\AmtAlsRpt;
use App\Model\Course;

trait CourseTrait
{
    public function getRecommendCourses(AmtAlsRpt $report, array $levelStatus)
    {
        $this->defaultLevel = $report->replica->getLevel();

        if ($this->defaultLevel >= 15) {
            return $this->procHighCourse($levelStatus);
        }

        if ($this->defaultLevel >= 5) {
            return $this->procMiddleCourse($levelStatus);
        }

        return $this->procLowCourse($levelStatus);
    }

    public function procLowCourse(array $levelStatus)
    {
        $container = [];

        if ($this->isFitOneByOne($levelStatus)) {
            $container[] = Course::COURSE_ONEBYONE;
        }

        if (empty($container)) {
            return collect(NULL);
        }

        return Course::find($container);
    }

    public function procMiddleCourse(array $levelStatus)
    {
        $container = [];

        if ($this->isFitBrand($levelStatus)) {
            $container[] = Course::COURSE_BRAND;
        }

        if ($this->isFitMuscle($levelStatus)) {
            $container[] = Course::COURSE_MUSCLE;
        }

        if ($this->isFitAgile($levelStatus)) {
            $container[] = Course::COURSE_AGILE;
        }

        if ($this->isFitPlan($levelStatus)) {
            $container[] = Course::COURSE_PLAN;
        }

        if (empty($container)) {
            return collect(NULL);
        }

        return Course::find(array_slice($container, 0, 2));
    }

    public function procHighCourse(array $levelStatus)
    {
        $container = [];

        if ($this->isFitMuscle($levelStatus)) {
            $container[] = Course::COURSE_MUSCLE;
        }

        if ($this->isFitAgile($levelStatus)) {
            $container[] = Course::COURSE_AGILE;
        }

        if ($this->isFitPlan($levelStatus)) {
            $container[] = Course::COURSE_PLAN;
        }

        if ($this->isFitBrand($levelStatus)) {
            $container[] = Course::COURSE_BRAND;
        }

        if (empty($container)) {
            return collect(NULL);
        }

        return Course::find(array_slice($container, 0, 2));
    }

    public function isFitBrand(array $levelStatus)
    {
        $sum = 0;

        $reds = ['视觉', '听觉', '触觉', '前庭觉', '本体觉'];

        foreach ($levelStatus as $categoryName => $level) {
            if (!in_array($categoryName, $reds)) {
                continue;
            }

            if ($this->isRedLevel($level)) {
                $sum ++;
            }
        }

        return $sum >= 2;
    }

    public function isFitMuscle(array $levelStatus)
    {
        $reds = ['本体觉', '姿势控制'];

        foreach ($reds as $red) {
            if ($this->isRedLevel(array_get($levelStatus, $red))) {
                return true;
            }
        }

        return false;
    }

    public function isFitAgile(array $levelStatus)
    {
        $reds = ['移位能力', '协调能力'];

        foreach ($reds as $red) {
            if ($this->isRedLevel(array_get($levelStatus, $red))) {
                return true;
            }
        }

        return false;
    }

    public function isFitPlan(array $levelStatus)
    {
        $redsForD7own = ['动作计划'];

        if (7 <= $this->defaultLevel) {
            foreach ($levelStatus as $key => $level) {
                if ('动作计划' === $key) {
                    continue;
                } 

                if ($this->isRedLevel($level)) {
                    return false;
                }
            }

            return true;
        }

        $redsFor7Up = ['视觉', '听觉', '触觉', '前庭觉', '本体觉'];

        if (7 > $this->defaultLevel) {
            foreach ($levelStatus as $key => $level) {
                if (in_array($key, $redsFor7Up)) {
                    continue;
                } 

                if ($this->isRedLevel($level)) {
                    return false;
                }
            }

            return true;
        }
    }

    public function isFitOneByOne(array $levelStatus)
    {
        $red = 0;

        foreach ($levelStatus as $level) {
            if ($this->isRedLevel($level)) {
                $red ++;
            }
        }

        return $red > floor(count($levelStatus)/2);
    }

    protected function isRedLevel($level)
    {
        return ($this->defaultLevel - 2) >= $level;
    }
}