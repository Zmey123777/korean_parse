<?php

namespace App\Services;

class CarModelMatcher
{
    private const CAR_MANUFACTURERS = [
        'Hyundai' => '현대',
        'Genesis' => '제네시스',
        'Kia' => '기아',
        'Chevrolet (GM Daewoo)' => '쉐보레(GM대우)',
        'Renault Korea (Samsung)' => '르노코리아(삼성)',
        'KG Mobility (SsangYong)' => 'KG모빌리티(쌍용)',
        'Other Manufacturers' => '기타 제조사',
    ];

    private const CAR_MODELS = [
        'Grandeur' => '그랜저',
        'Avante' => '아반떼',
        'Sonata' => '쏘나타',
        'Santa Fe' => '싼타페',
        'Palisade' => '팰리세이드',
        'Starex' => '스타렉스',
        'i30' => 'i30',
        'i40' => 'i40',
        'ST' => 'ST',
        'Galloper' => '갤로퍼',
        'Granada' => '그라나다',
        'Grace' => '그레이스',
        'Nexo' => '넥쏘',
        'Dynasty' => '다이너스티',
        'Lavita' => '라비타',
        'Marcha' => '마르샤',
        'Maxcruze' => '맥스크루즈',
        'Venu' => '베뉴',
        'Veracruz' => '베라크루즈',
        'Verna' => '베르나',
        'Veloster' => '벨로스터',
        'BlueOn' => '블루온',
        'Santamo' => '산타모',
        'Scoop' => '스쿠프',
        'Staria' => '스타리아',
        'Stella' => '스텔라',
        'Solaris' => '쏠라티',
        'Aslan' => '아슬란',
        'Ioniq' => '아이오닉',
        'Ioniq 5' => '아이오닉5',
        'Ioniq 6' => '아이오닉6',
        'Atos' => '아토스',
        'Equus' => '에쿠스',
        'Accent' => '엑센트',
        'Excel' => '엑셀',
        'Elantra' => '엘란트라',
        'Genesis' => '제네시스',
        'Casper' => '캐스퍼',
        'Kona' => '코나',
        'Cortina' => '코티나',
        'Click' => '클릭',
        'Terracan' => '테라칸',
        'Tuscani' => '투스카니',
        'Tucson' => '투싼',
        'Trajet XG' => '트라제 XG',
        'Tiburon' => '티뷰론',
        'Pony' => '포니',
        'Presto' => '프레스토',
    ];

    public function getManufacturerKoreanName(string $brand): ?string
    {
        return self::CAR_MANUFACTURERS[$brand] ?? null;
    }

    public function getModelKoreanName(string $model): ?string
    {
        return self::CAR_MODELS[$model] ?? null;
    }
}