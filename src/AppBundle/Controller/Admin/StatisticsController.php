<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("admin/statistics")
 */
class StatisticsController extends Controller
{
    private $months = [
        "1" => "Januari",
        "2" => "Februari",
        "3" => "Mars",
        "4" => "April",
        "5" => "Maj",
        "6" => "Juni",
        "7" => "Juli",
        "8" => "Augusti",
        "9" => "September",
        "10" => "Oktober",
        "11" => "November",
        "12" => "December"
    ];

    /**
     * @Route("/", name="admin_statistics")
     */
    public function indexAction(Request $request)
    {
        $cities = $this->getCityRepository()->findAll();

        $city = $request->query->get("city", "");
        $year = $request->query->get("year", date("Y"));
        $type = $request->query->get("type", "");

        $matches = $this->getConnectionRepository()->getMatches($city, $year, $type);
        $matches = $this->structuredMatches($matches);

        $years = $this->getConnectionRepository()->getYearSpan();

        $parameters = [
            "matches" => $matches,
            "cities" => $cities,
            "years" => $years,
            "params" => array(
                "city" => $city,
                "year" => $year,
                "type" => $type,
            )
        ];

        return $this->render('admin/statistics/index.html.twig', $parameters);
    }

    protected function structuredMatches($matches)
    {
        $structuredMatches = [];
        foreach ($matches as $match) {
            $month = date("n", strtotime($match["created_at"]));
            $structuredMatches[$this->months[strval($month)]][] = $match["created_at"];
        }

        $matches = [];
        $totalCount = 0;
        foreach ($structuredMatches as $key => $structuredMatch) {
            $matches[$key] = COUNT($structuredMatch);
            $totalCount += $matches[$key];
        }
        $matches["Totalt"] = $totalCount;

        return $matches;
    }

    protected function getConnectionRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:Connection');
    }

    protected function getCityRepository()
    {
        return $this->getDoctrine()->getManager()->getRepository('AppBundle:City');
    }
}