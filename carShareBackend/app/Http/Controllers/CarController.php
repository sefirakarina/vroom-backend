<?php
namespace App\Http\Controllers;

use App\Car;
use App\Location;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Intervention\Image\Exception\NotReadableException;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $car;
    public function __construct(Car $car)
    {

        //$this->middleware('auth:api', ['except' => ['index', 'show', 'getByAvailability', 'storeTest2', 'testImg']]);
        $this->car = $car;
    }

    public function index()
    {
        $car=Car::join('locations', 'cars.location_id', 'locations.id')
            ->select('cars.*', 'locations.latitude', 'locations.longitude', 'locations.address', 'locations.slot', 'locations.current_car_num')
            ->get();
        $array = Array();
        $array['data'] = $car;
        if(count($car) > 0)
            return response()->json($array, 200);
        return response()->json(['error' => 'car not found'], 404);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request !=null){

            // Handle File Upload
            if($request->hasFile('cover_image')){
                $imagedata = file_get_contents($request->file('cover_image'));
                $base64 = base64_encode($imagedata);
            } else {
                $base64 =
                    'iVBORw0KGgoAAAANSUhEUgAAAdMAAAGpCAYAAADWR9wKAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAAEnQAABJ0Ad5mH3gAACYkSURBVHhe7d0Hd9vGtvbx9/t/jxP3XuLee6+xHTc5bpF7XGTHlsW7HgZ8g6OzSQB7DwYA9f+ttdc9N5YGEEXxwQBT/t8IAACEEKYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmAAAEEaYAAAQRpgAABBGmyGZ5eXn08+fPUA2Bdd51CsBwEabI5siRI6NffvnFXdu2bRutrKwUrfXX+vXrzfOfVfv37y++G8AQEabIJhqmqkuXLhWt9RdhCqw9hCmySRGmqsXFxaLFftqwYYN53rOKMAWGjTBFNqnCdM+ePUWL/UTPFFh7CFNkkypMVdevXy9a7R96psDaQ5gim5Rhqnr37l3Rcr8QpsDaQ5gim9RheuDAgaLlfiFMgbWHMEU2qcNUdefOnaL1/ti4caN5rrOKMAWGjTBFNm2Eqerz58/FEfqBMAXWHsIU2bQVpseOHSuO0A+EKbD2EKbIpq0wVT18+LA4SvcIU2DtIUyRTZthqgD7+++/iyN1a9OmTeY5zirCFBg2whTZtBmmqjNnzhRH6hZhCqw9hCmyaTtMVU+fPi2O1h3CFFh7CFNkkyNMt2/fXhytO4QpsPYQpsgmR5iqut5ZZvPmzeZ5zSrCFBg2whTZ5ApTVZc7y9AzBdYewhTZeML00KFD5n+vqr179xZHzY+eKbD2EKbIxhOmN27cGB0/ftz8t6rS93Zhy5Yt5vnMKsIUGDbCFNl4wvT8+fOjt2/fmv9Wp7rYWYaeKbD2EKbIxhOmJ06cGH/v6dOnzX+vqoMHD46/Pyd6psDaQ5giG0+YTsLw/fv35r/Xqbt3747byIUwBdYewhTZeMK0vGepprxYX1OnPn36VLTSvq1bt5rnMKuGEqY/f/4cff36dfThw4fRq1evxotkaF3kx48fj168eDF68+bN+LX+/v178R3A2kCYIptIz1SWl5dHO3bsML+uqjSIKZd56ZkqOBWQ165dG1/UNJ3ys27duvFroWUeFbhfvnwpWgbmD2GKbKJhKn/88Yf5dXUq184yQ+6ZarDXzZs3x1OSFIbWuUZKU5auXLkyDmlgnhCmyMYTplbInDt3zvzaqtLOMt++fStaac8Qw/T169fuQV7e0vvh2bNnxRkAw0aYIhtPmFqLL2irNU9gqc6ePVu00p4hhamee2rEtHVOuUq94D5sUABEEKbIxhOme/bsKb77v2nAi/X1dUq3its0hDBdWlpyL4bRVh09erQ3e9ICTRGmyMYTpjt37iy++395b0tqZ5mVlZWilfS2bdtmHndW5QzTly9fus4xR+niSdOggKEhTJGNJ0w1encajQ7Vc1Dr+6rq8uXLRSvpKaytY86qXGGqObfW8ftUWkGKAUoYGsIU2aQOU7l//775fXXqzz//LFpJq689Uy3NaB27r5Vr9DWQAmGKbNoIU/E++2trZ5k+9kz79ny0bjHaF0NBmCIbT5gqmKr89ddf5vfWKc2pTK1vYXr9+nXzmEMoLfrw+fPn4icB+oswRTZthancuXPH/P46pYUKUupTmC4sLJjHi5RWQzp58uT4ubNe97afw2rqDNB3hCmyaTNM5fDhw2YbVZX6w7ovYart59avX28er2lpfu6TJ09GP378KFqf7fnz56MLFy64lla0SusyA31GmCIbT5hqME9dkX1PU+4s41k/OHWYal3dffv2mcdqUqdOnQptEqAF79WDtdpuWuplA31FmCKbtnumomegVjt16uPHj0UrMX3omd6+fds8Tt3SwhMpVyXShU403Lndiz4jTJFN2z3TCQWT1VZVTTYij+q6Z6peaeT2qs6/jYUTtHVbNFDbms4ERBGmyCZXmGq9WautOpVibmPXYRrple7evXs8Orot0UDVwCegjwhTZJMrTEV7cFrtVZX27IzuLKMlEK22Z1WqMFWvVCsIWceoU9rcu23aocY6dt3SxuRA3xCmyMYTpnp256V1Xq02qyq6s0yXPdNIr1TzUXPxXuyoLl68WLQC9Adhimxyh6nWd7XarFORwTdd9kw9Sxmqfv3116KFfHRL2TqXqtLFCtA3hCmyyR2movmJVrtVpQ9s3TL12LVrl9nmrEoRpho0ZLVdp7rYTzSy0EZkug7QBsIU2XQRpgpEz21XlXdnma56pt6ViBT+XdDepdb51KlHjx4VrQD9QJgiG0+YaopHlDYDt9quU56pGF31TL2L2f/2229FC/np+bR1TlV17ty5ogWgHwhTZNNFz3RCH75W+1XleZbYVZhqJLLV9qxat27deJWirmjZQeu8qqqr3jQwDWGKbLrqmYqmu3injNy4caNopZ4uwtQ7t1ZzPru0tLRknled0ubwQF8QpsimyzAVPWezjlGnmsy/7CJMFfhWu1WlDcO75h2BrPmqQF8Qpsim6zAVLdxuHaeqmqwL65nyEQ1Tzb202q2q33//vWihO8eOHTPPrarYOBx9QpgiG0+Y6tZsSro16N2WTFM56ugiTE+fPm22W1V96N1pf1Tr3KoqxdKPQCqEKbLpQ5jK/fv3zWPVqTrr1nYRpp7XVvX58+eihe5oNLF1blVV9+IGyIEwRTZ9CVPx3lrU9JMqXYSpd/F4zfXsmnqY1rlVVc7lD4EqhCmy6VOYqodpHa9OPXjwoGjF5lkTOBqmnoUiVCsrK0UL3fHOA2aNXvQJYYpsPGGquZNt8S5np4DXVmLTdBGmnjmmbb62TWhhDOv8qkqDyYC+IEyRTZ96phMapWsdt6rOnDlTtPC/9u7da37PrIqEqXqXVptVtX379qKFbi0uLprnV1XR3X2AlAhTZNO3nqm8ffvWPG6d0u1Ji+f5ZbRnunHjRrPdWbVhw4biu7ulRfat86sq79rJQBsIU2TjCVOFRNtu3bplHruq9JxyeXm5aOVfBw8eNL9+VkXDVL1Mq92q8u6Mk5J3MQ393oC+IEyRTR97phNag9c6flVpi7fVDh8+bH7trIqGqff8Zz37zcW7242mOAF9QZgim772TMX73E718uXLopV/eKbdRMPUE+CqOvNm2+ZdCvHJkydFC0D3CFNk0+cwlatXr5rnUFWrd5Y5efKk+XWzKhqm3mUSFxYWiha64z13z/Z4QFsIU2TT9zAVzyL1qvLOMhrpa33NrIqGqXdtXus2dW7e1/zdu3dFC0D3CFNkM4QwffHihXkedWry4X7hwgXz32dVNExv375ttltV0eNGaWs867yqqu0pU0BThCmy8YRpF9M3vL28o0ePjr/f8wwwGmqRKT5djujV82brnKpKt9KBPiFMkc0QeqaicPHusakRpvfu3TP/bVal6CFu3brVbLuqNDWlK94LF73GQJ8QpshmKD1T8a4Xq/PV2r3Wv82qFGHq3YZNI4G7oIsWXSxZ51RVHz58KFoB+oEwRTaeMNXeo13xDCRSaUNz67/PqhRh6t19RdXFYB7PRYdqx44dRQtAfxCmyGZIPVPR9mSeBeQ9lSJMtTep1XadOn/+fNFKPhpEZJ1LVbEmL/qIMEU2Q+uZinepu6aVIkzFu3C/qmpruZS8G4Kr3rx5U7QC9AdhimyGGKaiDcGtc0tZqcL02bNnZvt1SncBcqyIFNlLlm3X0FeEKbIZaph++fLFPLeUlSpMxbPQ/qQOHDgwnvvZFg068i59qKJXir4iTJHNUMNUPNNdmlTKMPWORJ6UlkfUBURq0SClV4o+I0yRjSdM161bV3x39yJBUFUpw1TUnnWcurVnz56kvUANjvL8/stFrxR9Rpgim6GH6cePH81zTFGpw1Q7qljHaVpaVCG6TdvNmzfHv0er/bp15cqVojWgnwhTZDP0MBXvGrhVlTpM5fr16+axmpZutWtB/OfPnxctV1MvUssq7t6922yzSU2WaQT6jDBFNt7bfH0TvYVqVRthKp7t4GaVRvxqdPO5c+dG165dG09xuXPnzvh/a67qiRMnxosqWN/rqe3bt4+WlpaKnwboL8IU2cxLmEYWlZ9WbYXpjx8/Rnv37jWPOYRiz1IMBWGKbOYlTCXVLdRJtRWmovDXrVrruH0uFrPHkBCmyGaewlQ04tU6X0+1GaayuLg4vmVqHbuPlXM1JiAFwhTZzFuYKqCs8/VU22Eqmjva5vSeFKWt77i1iyEiTJHNvIWpXL582TznppUjTCc0UMg6h65LKzdpPiowRIQpspnHMJUUt09zhqm0NcXHWxodDAwZYYps5jVMI4vLTyp3mIqmnGgxBOt8cpX2jO1iL1UgNcIU2cxrmIp6Vta5160uwnSii1A9ffr0eJQxMC8IU2Qzz2G6vLzs3uxa1WWYTihU7969Ozp27Nh4cQbrPCOlHWm0KhJr7GIeEaYATBpVq5WNFPSbNm0yA3Jabdy4cTx1SMsQPn36dPT9+/eiVWA+EaYAallZWRn3Xj98+DCeFqSt3jQfVGH56tWr0fv378f/rq3WgLWGMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAFACCIMAUAIIgwBQAgiDAt+fnzp7sAAGsXYVry66+/jn755ZfGtXv37qIFAMBaRJiWEKYAAA/CtIQwBQB4EKYlhCkAwIMwLSFMAQAehGkJYQoA8CBMSwhTAIAHYVpCmAIAPAjTEsIUAOBBmJYQpgAAD8K0hDAFAHgQpiWEKQDAgzAtIUwBAB6EaQlhCgDwIExLCFMAgAdhWkKYAgA8CNMSwhQA4EGYlhCmAAAPwrSEMAUAeBCmJYQpAMCDMC0hTAEAHoRpCWEKAPAgTEvWapj+/Plz9Pbt29HLly9HT548GT148GC0sLAwev78+ej169ejlZWV4ivz0TEn5/THH3/8/3NaXFwcffjwYfTt27fiK4dteXl5tLS0NP6ZXr16NX7NHz9+PHr48OH4d/HixYvRmzdvxv+ur5tH379/H/3111+jP//8c/To0aNxPXv2bPze+/z5c/FV+Pvvv8evk/4m9BrpfaL//e7du/F7o4u/U/yLMC1ZK2GqD3AF1NmzZ8fnbv1M5Vq3bt3o8OHDo5s3b44+ffpUtJKeguPChQujffv2jY9pnUu5Nm7cODpx4sTo3r1747DpM33Q6UNPH4JXrlwZHTlyZLRt2zbz55pVO3fuHJ07d258caEP1qHRhZsuEs6cOTP+WfQ7tH7Ocm3YsGG0d+/e0fXr18cXFmvB169fx++VU6dOjV8nvQbWa7O6tmzZMv4efa/aQD6EaUkfwlShYB2jqq5du1a0MJ0+fBVW1vc3qePHj4+viFPQH/zly5dHmzdvNo/VpPbs2TPuvfaFAlTBoQ+3OhcHntJ79v79+8UR+0kBqt+LXof169ebP0eTqvMzf/z40fzeqtJ7sSu62Lp169bo4MGD5rl5av/+/eOLYPVq0S7CtGRew1Qf6hcvXjS/L1Jq88ePH8VRmrtx40aSD9fVpV6fbpl2Rb3+06dP1+5NpCj1SPQe0F2HPtGtyO3bt5vnHK1du3aNbwdbhhSmuqBs4++zXLoDoKBGewjTknkMUz2HUo/N+p4UpQ80PdtsQj3kQ4cOme2lrN9//704Yh669drma12ndPw+3ArVe+LYsWPmOaauS5cuFUf9lzdMdQs+p99++63Wre5UtWPHjt7fyRgqwrRk3sJUfzTW16YufRhM6yGsptueOT887t69Wxy5PQrttnpf3tIApq4okKxzarP0DLas7z3Tp0+fdnrhpTsnSIswLZmnMNVgDevr2qr//Oc/49GXs2hQhPW9bdft27eLM0gv5fOt1KVeT256LmqdS47SYLSJPvdMdfFlHTt3aewD0iFMS+YlTPUhan1N26VbvtMGOuTqJU+rprei6+pzmKrUA8qlyyCd1Pnz58fn0tcw1d+pddyuSuML+vacfagI05J5CNOuen+T0rSN1dRjtb42Z+lDow19D1ONks4xhaYPQTop3dr3hunVq1eLnyg9TUWzjtl1HThwoDhDRBCmJUMPU01wzzmCdFpp0NOEpkVojqD1dbmrjYEX+iCyjtWnUuC3qU9BOilN3bL+e1W1FaZHjx41j9eXsgZxoRnCtGToYapnINa/5a7ylW7bQ/6blEYypqYFJqxjNSkNXlI7Gv2qUc66+PAs6DCrtCBGG/ry/G91eecttxGmatM6VtPSXGUNWtJdFt3O1jNizSNN9V7RlC74EaYlQw5TBYX136eV5pzpdpieEalnkfrDW3+YTXoHOn99OOiiQB/QGkClW8apb6Nqub6UmvS69WGoHsqdO3dG79+/r1yhRrdn1ZtO8Rq0MdhE579p0ybzeN7S35IuKrS4iBYb0HtB85EVHjl6d6nDVItVWMdpUro9rL+nWc82teCDxkro9bPaqFP6DGBxBz/CtGTIYTqr9CGukFLbs+j2rKYYWG00Lb2WdRZk0AekpsvM8uXLl/GH6tatW802mlTqKQFVH156DTTdQlOHdMvbSz3L6O3y1IOwUr1XtFyeQqzO/FgFuC4w1COz2orWZOxBCnpuG5kGpte36m/WoosQq706xZQZP8K0ZB7DVL0a9YKa0NV0W8vflavp1A1dNau3arXVpFJefSsIrGPo2bV61ykX5NdqU+q9W8erUymfi6mnZB2jaanX6aVQTX1HJWWYaj1r6xh1KjpPWBdf3tXF1MtFc4RpybyF6eqJ7E2oF5NivdxpVR6k1FR0wItWKkpl9WINuu2pgNBOKG3x3vbV+zuVaRcRdUu3cpte5Fn0Oqe8/ZsqTDW32Wq/qnQRm2o6k3ZY8gxI7HJ94iEjTEvmKUxTTAXR+rZW29HS9J0IPTuK3PJM+WFRvvWsQUQ5pqFEbh+m6JXrtrzVdt3SIKvU24WluGOhShGm+tm0VrLVflWlHgSkC0frOLNKF4Spfz9rAWFaMi9hqt5SqtuLCh7rGN7S85wUtIC61X6d0u23VCYDcHI/a/Lu/pOi1xPpCeoiqK1BLil6qLo1H+VdNKWtFas8d3JS3r1ZKwjTknkJ05TzKRXKqeauqueWkrd3ql5DKnptulgH17sQRnSFn8j7U7cw1atuiwaqRQeppQhTz3PcNpf208Au65izqu25yfOIMC2ZhzBNHViSYg9UVeql7TTFxDpOndIH79A1nQ6livagdRvUardO6ffVtugt6MiAKNF0M6vdqmp7px/PZ9vS0lLx3aiDMC2ZhzBtY3Nszc20jtWkyouQp6JBUtax6lRkAFRfeG7fRXtA3p6fnpPmEhlFG+2Zei5wNI+0bZ4Lz9RzsucdYVoy9DDVoJS2Bg5Et02ru0VbU96BHjkXgG/LvXv3zJ9tVkVCTdMtrDbrVNWOQinpQsk6hzoV6ZlqSonVZlXl2Mjec6s31fiGtYIwLRl6mLZ5hRtZhUfTKNri3YA6OqK4Dzyr6+g5s5dWzbLarKqUfx91eafuRMLU0/vL+do0Hfugvy3UR5iWDD1M2+xtRaYe6EO4Ld61f7Wi0tB5eoqRCxvvghHR55Ae3vdrpDfmubDL2ftrevtbA6lQH2FaMvQwbXPAgHcSukpr9LbFO+CjrWkIOej3rNfUM20p8gHpeR6oSrE4Q1PerQgj4eZ5FJLzueTJkyfNc5hVnz59Kr4bVQjTkiGHqeaWtikySrLNxbP1LNY6ZlWlXDauDdpOTz1P3TrUIu961pli6TwNIPLQmrhWe1WlAO6Cd8ERb5hqtSGrvaqq2uwgJc9ayjme584LwrRkyGHa5jw18Y7obfuZUFfzLVPQVb8CUwOJtG6uFh2ILtNXVd6eqXfwUdvvy2m8m4N7w1S3sq32qionz63veRiolwthWjLkMFXvpU3eK30FRJt0C9E6blXlDFMtf6jQ13NafaDpfeZdhDxa3p6pd/BRikUQPPSaW+dTVd7n+6nmYvetWAmpPsK0ZMhh2nY4eIf9t73Mnnp31nGrqu3FvLXdmpY8jOzy0kZ5w9Q70KurDaf1+lvnU1XeMPU8jxxCDXlsQW6EacmQw7TtEZNawN06blW13WPWcofWcauqrTBVT1nPprrqeVaVN0x1UWS1V1V6P3chd5hGForoc6XeLH2eEaYlPDOdzhumbf8xagsu67hVlTpMNWBoCLf6vOsSexeR72pJutxhGt24va/V9sXwPCFMS4Ycpm3fjvGGadu3U71hmnKjbM9KRF2VN0z3799vtldV2tC8C7nDdPW+tvNSun2NegjTEsJ0OsLUpnas9vta3jD1jDLWLjFdyR2mfb2tHy1WQaqPMC0hTKcjTP9Xiv0zPaXFAdRT1Abw1r/Pqs2bNxdn34xnDWRvcKeQO0yttuahUu79O+8I05Ihh6lWKGrTvIWpRqdGaPS01W6q2rNnz3gksJ45a39aLQqwets4z4Lu3jC12qqqLpejyx2mnouNIdSBAweKnxBVCNMSwnQ6b5imfDZp6SJMvUvVWaUNBDRwSc9dFY5N9ln1hOmmTZuK727GcxtTPeiu5A7TXbt2me0NvfSZiHoI0xLCdDpvmEZ7gFVyh6mWRoxsR6edOzRCUitKRQfn5AxTb8+rKxpFbJ1PVXnD1DNAS8+UdQeiTukzxvrvk9Jo4knN+rcmpc9DPUpAPYRpyZDD1PshUNe8hal6gx5a0cdqr6oUovodaWWeVDzrwXrD1LvMYVejeb0rY3n/jjzPz9Xbx/wgTEuGHKZtb+VEmP5zLE+vVLcAtVZsap4Nn723Xvft22e2V1Vd7TriXXjeG6aeReRVmB+EaQlhOh1hOhqPmLbamlXqCbYVKJ7F571h6l3hR7sNdeHhw4fm+VSVN0y9U6Ta3FEJeRGmJYTpdN4w9d5Orcsbpp6VXTybP7e5CbknMHS72cO79mxXu/PofWedT1V5w1Rb5VntVRW7sswPwrSEMJ2OMB2Ne5lWW9Oq7dGsng3bvWHq7XlpH9YuaOCNdT5V5Q1T765KCmHMB8K0ZMhhulYXus8Vpp7nk21vP+dZfN4bpgsLC2Z7dSr3rUzvRuYqb5iurKyMR+dabc6qtt8jyIcwLSFMp5u3MD179mzRQj3aTs1qZ1a1fYvTsx6sdwSpFvK32qtTd+/eLVrJQ3dprPOoU94wFc0ZttqcVQpgzYnF8BGmJUMO07Y3YfaGqTbDblOuMPUsZt/m3F+NDraOWVWR6Rjexdz1d5WTVl6yzqNORcLUuyqWVrjC8BGmJYTpdGs9TNXzt9qZVW0OPvJ+cEfC9NSpU2abdUqLVOTw4MED8/h1KxKm2gjdarOqduzYUbSAISNMS4YcpteuXStaaMe8hanmBTbhGYDT1gWOlhy0jlenIju56Hat1Wad0t+Iniu2Sa+L1h62jl+3ImHqXXVJlftWONIjTEsI0+nWephqwX6rnVnV1l6Q3pG1qkiYem8tT6rtZ8jaGMA6bpOKhKl4F2/QHYPXr18XrWCICNMSwnQ6b5g2Da2mcoWpZxpKG1Nj9HzNOlbdioSpRAOrrUUcvPM8V1c0TL1TZFR6Jt3VilHS9qYU844wLSFMp5u3MNW0kia8IaaBS6lEg1QVDdNnz56Z7TYptZGSd71kq6JhKppba7Vdp7RsY+5A1bPeye1x+BGmJYTpdGs9TL2DSzSyVNNKonR8BaF1jCYVDVNJsd1YqkCN3PK2KkWYeqZRlUuLg2heb9u+ffs2nrpWPjb8CNMSwnQ6b5g2Da2mcoWpPnisduqU3ldN9ildLTLwx6oozy1vq3ShpUE7Hgpj7ypHsypFmIpG6FrtNyk9Y05xIbaans1qLIN1TPgRpiWE6XRrPUwlcvtOz081baMJLWTv2SezqqK0jVyKsFBpRSYtOamftYp+19qY3btOcJ1KFaaeTQimlaZxaQWuCP39ai3nqoUl4EeYlhCm0xGmvl1jVpdukapn9/Lly/GydxNaBUe9V234rd+lNme2vr9c3sUJUkjx7HR1bd26dbw7jXpNWsVIPXK9Fvr/m+4X6r3VmipMxTM3eVZpgJLet3p2/u7du/Hoar2HtGes3j+6e6L/puDV+0uvny48mrxP4EeYlhCm0xGmsXmEs2rLli3mf59V2qzbGxipaHs9q/2uS9OYvNN4UoapeDYN77LgR5iWEKbTEab/8K48lLq0olDXYarekELdOkZXpZ6/bkP3JUxTLCSRs+BHmJYQptMRpv9oq3fapCa/667DVBTq1jG6KI1Unjxb7EuYip6fDiVQ4UeYlhCm0xGm/0ox39Nb5alGfQhTUVikmLYTrfKCEH0KU9HfdZ3n4F0X/AjTEsJ0OsL0v+n1ttpts7T6UFlfwlQ0cEojlq3j5Si9FmXeMG1zpx/t69r3Z6jwI0xLCNPpCNP/pV6i1XYbZZ1vn8JUtJSeFhywjtlW6RmpNW3Eu/9q0+lLHqsXSuhLaeAW/AjTEsJ0Ou95zXOYSuoFFaya9rvtW5iKninnCgvNT9VgI8vbt2/N76mqttYOXk1TVyLzllOV5vlqCo96zYghTEsI0+nev39vHreq2g5TzbGzjltVKc9LPTLve2dW6Rmbbp9O08cwndB5HzhwwDx+tLR+7dOnT4sj2bwDoxRyOSm8u3iWqtdQz4d1MYo0CNMSwnS6voaprqit41ZVG+el9XNT9DZ061Kr1VTpc5hOaIP0Y8eOmefRtPT3ufrZ6DRaKclqo6rUo+2CBrVp8/U2b5Nr9SPtrqPnyUiPMAUS09qnWi1JIVLnw1Ffow863W6LLhvXV7roURBqRZ6600S04o8G7HgCwLslW5dboE3o/aMVoPSe8ISrBoLproBut+sxhHrb3jWQUR9hCrRMg2HU49GHmm7rqdep5fgWFxd78eHdhcnyiQoO3bLVwB/17PUa6bWK3n70Lq6hJfn6Rq+Vlg3UXSu9ZybvIf1f3c7Wf5ssL0hodocwBTB3vM9rV1ZWihaAZghTAHNFPTkrKKtKW7oBXoQpgLmikcRWWFaVptoAXoQpgLmiKR9WWFaVRgADXoQpgLmiqWpWWFbVWh0MhjQIUwBzY2FhwQzKqtI0HCCCMAUwNw4fPmyGZVVpwQQggjAFMBc079IKyjp17969ohXAhzAFMHha7tK7BZwWe2eNWkQRpgAGTfNKIxsNXL16tWgJ8CNMATT24sWL0datW2fuapOD1vzVer9WSNYtLWsIRBGmABpTmE7CSIN+tA1dbgrynTt3/lcwNi0tBg+kQJgCaKwcppM6ceLEeLH6tmn93FSbsmuBeCAFwhRAY1aYTmrLli2jixcvJu+t6nas9u2tu4VbVdErRUqEKYDGZoVpuXQb9syZM+PNr7WFWBM/fvwYb8mmvT21r6nVvrf2799fHAVIgzAF0FjdMF1dGrSk7dG0cbpC9tKlS6Pr16+P/+/Zs2dHx48fHx06dCg0Oreq1q9fP55KA6REmAJozBumfajHjx8XPwWQDmEKoLGhhumVK1eKnwBIizAF0NgQw1S3k4G2EKYAGhtSmG7atCnLlB2sbYQpgMa0YIIVXH0rDWb6+PFjcdZAewhTAI0tLi6a4dWnunz5cnG2QPsIUwCNaU3cc+fOjXdcsYKsq9KCETdu3Bh9/fq1OFMgD8IUQMjCwkJ4sflo7d69mz1J0SnCFEASy8vLo2fPno2nn+zdu9cMvVSlxR+0wMOtW7c637kGEMIUQCuWlpbGo2jv3LkzunDhwnhJwF27do3WrVtnBmS59DW6Zauv37dv3+jIkSPj9X4fPXrEgCL0EmEKIDv1YvXcVYH76dOn8bq9WuJPi9l///69+CpgOAhTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAgghTAACCCFMAAIIIUwAAQkaj/wPTYAuGL5WDGAAAAABJRU5ErkJggg=='
                ;
            }

            $locationCapacity = Location::where('id', $request->location_id)
                ->select('slot', 'current_car_num')
                ->first();

            $availableSlot = $locationCapacity->slot - $locationCapacity->current_car_num;

            if($availableSlot > 0){


                try{
                    $car = Car::create ([
                        'type' => $request->type,
                        'location_id' => $request->location_id,
                        'plate' => $request->plate ,
                        'capacity' => $request->capacity,
                        'image_path' => $base64,
                        'availability' => $request->availability,
                    ]);

//                    $car = Car::create ([
//                        'type' => "dfdfsdffff",
//                        'location_id' => 1,
//                        'plate' => "BABdddABABAB" ,
//                        'capacity' => 3,
//                        'image_path' => $base64,
//                        'availability' => true,
//                    ]);


                    $location = Location::find($request->location_id);
                    $location-> current_car_num = $location-> current_car_num + 1;
                    $location->save();


                    return response()->json(['message' => 'successfuly create car'], 200);
                }catch (\Exception $e){
                    return response()->json(['error' => 'qFailed to add car, plate number already exist'], 404);
                }


            }else{
                return response()->json(['error' => 'location is full'], 404);
            }
        }else{
            return response()->json(['error' => 'request is empty'], 404);
        }

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $car=Car::join('locations', 'cars.location_id', 'locations.id')
            ->select('cars.*', 'locations.latitude', 'locations.longitude', 'locations.address', 'locations.slot', 'locations.current_car_num')
            ->where('cars.id', '=', $id)
            ->get();
        $array = Array();
        $array['data'] = $car;
        if(count($car) > 0)
            return response()->json($array, 200);
        return response()->json(['error' => 'car not found'], 404);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        try{
//
//            $currentLocation = Car::where('id', $id)
//                ->select('location_id', 'image_path')
//                ->first();
//
//            $currentLocationId = $currentLocation->location_id;
//
//            if($request->hasFile('cover_image')){
//
//                $imagedata = file_get_contents($request->file('cover_image'));
//                $base64 = base64_encode($imagedata);
//
//                $newImg = $base64;
//
//            }else{
//                $newImg = $currentLocation->image_path;
//            }
//
//            $car = Car::where('id', $id)->update([
//                'type' => $request->type,
//                'location_id' => $request->location_id,
//                'plate' => $request->plate ,
//                'capacity' => $request->capacity,
//                'image_path' => $newImg,
//                'availability' => $request->availability
//
//                'type' => "fdsfsdfds",
//                'location_id' => 1,
//                'plate' => "fdsssssfssfddhdffs" ,
//                'capacity' => 2,
//                'image_path' => "fsdfsaaa",
//                'availability' => 1
//            ]);
//
//            if ($car != null) {
//
//
//                if($currentLocationId != $request-> location_id){
//
//                    try{
//
//                        $locationOld = Location::find($currentLocationId);
//                        $locationOld-> current_car_num = $locationOld ->current_car_num -1 ;
//                        $locationOld->save();
//
//                        $locationNew = Location::find($request-> location_id);
//                        $locationNew-> current_car_num = $locationNew ->current_car_num +1 ;
//                        $locationNew->save();
//
//                        return response()->json(['message' => "success adding car"], 200);
//
//                    }catch (\Exception $e){
//                        return response()->json(['error' => $e], 404);
//                    }
//                }
//                else
//                    return response()->json(['message' => "success adding car"], 200);
//            }
//            else
//                return response()->json(['error' => "car not updated"], 404);
//        }catch (\Exception $e){
//            return response()->json(['error' => $e], 404);
//        }

        if($request !=null){

            $currentLocation = Car::where('id', $id)
                ->select('location_id', 'image_path')
                ->first();

            $currentLocationId = $currentLocation->location_id;
            $newImg = null;

            // Handle File Upload
            if($request->hasFile('cover_image')){
                $imagedata = file_get_contents($request->file('cover_image'));
                $base64 = base64_encode($imagedata);

                $newImg = $base64;

            } else {

                $newImg = $currentLocation->image_path;
            }


            try{

                $car = Car::where('id', $id)->update([
                    'type' => $request->type,
                    'location_id' => $request->location_id,
                    'plate' => $request->plate ,
                    'capacity' => $request->capacity,
                    'image_path' => $newImg,
                    'availability' => $request->availability,
                ]);

                if($car != null){

                    if($currentLocationId != $request-> location_id){
                        try{

                            $locationOld = Location::find($currentLocationId);
                            $locationOld-> current_car_num = $locationOld ->current_car_num -1 ;
                            $locationOld->save();

                            $locationNew = Location::find($request-> location_id);
                            $locationNew-> current_car_num = $locationNew ->current_car_num +1 ;
                            $locationNew->save();

                            return response()->json(['message1' => $request -> all()], 200);

                        }catch (\Exception $e){
                            return response()->json(['error1' => $request -> all()], 404);
                        }
                    }
                    else
                        return response()->json(['SUccess message2' => $request -> all()], 200);
                }
                else
                    return response()->json(['error2' => $request -> all()], 404);

            }catch(\Exception $e){
                  return response()->json(['error3' => $request -> all()], 404);
            }
        }else{
            return response()->json(['error4' => $request -> all()], 404);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $car = Car::where('id', $id)->delete();

            if ($car != null) {

                $locationCarNum = Location::where('id', $id)
                    ->select('current_car_num')
                    ->first();

                $location = Location::find($id);
                $location-> current_car_num = $locationCarNum->current_car_num -1 ;
                $location->save();

                return response()->json(['message' => 'Car successfully deleted'], 200);
            } else {
                return response()->json(['error' => 'Car cannot be deleted'], 404);
            }

        }catch (\Exception $e){
            return response()->json(['error' => 'Failed to delete car'], 404);
        }

    }

    public function getByAvailability($availability){

        $car=Car::join('locations', 'cars.location_id', 'locations.id')
            ->select('cars.*', 'locations.latitude', 'locations.longitude', 'locations.address', 'locations.slot', 'locations.current_car_num')
            ->where('availability', $availability)
            ->get();

        $array = Array();
        $array['data'] = $car;

        if ($car != null) {

            return response()->json($array, 200);
        } else {
            return response()->json(['error' => 'no car with such availability'], 404);
        }
    }

    public function testImg(){

        try
        {


            $storagePath = public_path() . "/storage/cover_images/noImage.PNG" ;
            return Image::make($storagePath)->response();


        }
        catch(NotReadableException $e)
        {
            // If error, stop and continue looping to next iteration
            //continue;
        }


    }

    public function storeTest(Request $request)
    {
        try{

            if($request->hasFile('cover_image')){
                // Get filename with the extension
                $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
                // Get just filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // Get just ext
                $extension = $request->file('cover_image')->getClientOriginalExtension();
                // Filename to store
                $fileNameToStore= $filename.'_'.time().'.'.$extension;
                // Upload Image

                $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);

                return response()->json(['img_path' => public_path() . '/storage/cover_images/' . $fileNameToStore], 200);
            } else {
                $fileNameToStore = 'noImage.jpg';
            }

        }catch (\Exception $e){
            return response()->json(['error' => 'fail store img'], 404);

        }


    }

    public function storeTest2(Request $request)
    {


            if($request->hasFile('cover_image')){



                //$base64 = base64_encode($request->file('cover_image'));

                $imagedata = file_get_contents($request->file('cover_image'));
                $base64 = base64_encode($imagedata);

                //return response()->json(['base64' => Image::make($request->file('cover_image'))->resize(300, 200) ],200);
                return response()->json(['base64' => strlen(gzcompress(gzcompress($base64))) ], 200);
            } else {

            }




    }
}
