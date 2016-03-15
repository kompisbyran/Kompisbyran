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
        "01" => "Januari",
        "02" => "Februari",
        "03" => "Mars",
        "04" => "April",
        "05" => "Maj",
        "06" => "Juni",
        "07" => "Juli",
        "08" => "Augusti",
        "09" => "September",
        "10" => "Oktober",
        "11" => "November",
        "12" => "December"
    ];

    /**
     * @Route("/", name="admin_statistics")
     */
    public function indexAction(Request $request)
    {
        $city = $request->query->get("city", 2);
        $year = $request->query->get("year", date("Y"));
        $type = $request->query->get("type", "");

        $matches = $this->getConnectionRepository()->getMatches($city, $year, $type);
        $matches = $this->structuredMatches($matches);

        $cities = $this->getCityRepository()->findAll();

        $allConnections = $this->getConnectionRepository()->getAllConnections();
        $years = $this->getYearSpan($allConnections);

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

    protected function getYearSpan($connections)
    {
        $years = [];
        foreach ($connections as $connection) {
            $year = date("Y", strtotime($connection["created_at"]));
            if (!in_array($year, $years)) {
                $years[] = $year;
            }
        }
        return $years;
    }

    protected function structuredMatches($matches)
    {
        $structuredMatches = [];
        foreach ($matches as $match) {
            $month = date("m", strtotime($match["created_at"]));
            $structuredMatches[$this->months[strval($month)]][] = $match["created_at"];
        }

        $matches = [];
        foreach ($structuredMatches as $key => $structuredMatch) {
            $matches[$key] = COUNT($structuredMatch);
        }

        $matches["Totalt"] = COUNT($matches);

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