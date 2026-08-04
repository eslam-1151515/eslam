<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class LandingPage extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'title',
        'slug',
        'template',
        'color_theme',
        'content',
        'sections',
        'custom_css',
        'seo_title',
        'seo_description',
        'featured_image',
        'is_active',
        'facebook_pixel_id',
        'tiktok_pixel_id',
        'views_count',
        'conversions_count',
    ];

    protected $casts = [
        'content' => 'array',
        'sections' => 'array',
        'is_active' => 'boolean',
        'views_count' => 'integer',
        'conversions_count' => 'integer',
    ];

    /**
     * Scope للحصول على الصفحات النشطة فقط
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * زيادة عدد المشاهدات
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * زيادة عدد التحويلات
     */
    public function incrementConversions(): void
    {
        $this->increment('conversions_count');
    }

    /**
     * حساب نسبة التحويل (Conversion Rate)
     */
    public function getConversionRateAttribute(): float
    {
        if ($this->views_count <= 0) {
            return 0.0;
        }

        return round(($this->conversions_count / $this->views_count) * 100, 2);
    }

    /**
     * الحصول على الأقسام المجهزة للرندرة مع توفير الأقسام الافتراضية في حال خلوها
     */
    public function getParsedSectionsAttribute(): array
    {
        $data = $this->sections;

        if (empty($data) || !is_array($data)) {
            $data = $this->content;
        }

        if (is_string($data)) {
            $decoded = json_decode($data, true);
            if (is_array($decoded)) {
                $data = $decoded;
            }
        }

        if (empty($data) || !is_array($data) || count($data) === 0) {
            return self::getDefaultSections();
        }

        return $data;
    }

    /**
     * الأقسام الافتراضية الفاخرة لصفحة الهبوط (High-converting templates)
     */
    public static function getDefaultSections(): array
    {
        return [
            [
                'type' => 'hero',
                'title' => 'عروض حصرية لفترة محدودة 🔥',
                'subtitle' => 'اكتشف تشكيلة مميزة من أفضل المنتجات بأسعار استثنائية وجَودة لا تُضاهى، واطلب الآن قبل نفاذ الكمية المتاحة!',
                'badge' => '⚡ خصم يصل إلى 50%',
                'cta_text' => 'تسوق العرض الآن',
                'cta_link' => '#product-showcase',
                'bg_image' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?q=80&w=1200&auto=format&fit=crop',
            ],
            [
                'type' => 'countdown',
                'title' => 'ينتهي هذا العرض الحصري خلال:',
                'offer_badge' => '⏰ فرصة لا تعوض',
                'end_time' => date('Y-m-d H:i:s', strtotime('+24 hours')),
                'text' => 'سارع بالطلب الآن واكسب شحن مجاني للطلبات المؤكدة خلال هذا التوقيت!',
            ],
            [
                'type' => 'product_showcase',
                'title' => 'المنتج الأكثر طلباً في المتجر',
                'subtitle' => 'صُمم خصيصاً ليناسب احتياجاتك بأعلى مواصفات الجودة العالمية والأداء الاستثنائي',
                'product_id' => null,
                'product_name' => 'الساعة الذكية الفاخرة - الإصدار الخاص الجيل الثامن',
                'original_price' => 1200,
                'custom_price' => 599,
                'currency' => 'ج.م',
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop',
                'buy_button_text' => 'اطلب الآن واكسب الخصم',
                'features' => [
                    'ضمان استبدال واسترجاع بدون أسئلة لمدة 14 يوم',
                    'دعم فني متواصل ومساعدة فورية على مدار الساعة 24/7',
                    'خامات فائقة الجودة ومقاومة للمياه والخدوش والصدمات',
                    'توصيل سريع للغاية لجميع المحافظات والدفع عند الاستلام',
                ],
            ],
            [
                'type' => 'features',
                'title' => 'لماذا يفضلنا أكثر من 10,000 عميل؟',
                'subtitle' => 'نحن نلتزم بتقديم تجربة تسوق لا مثيل لها ومزايا حصرية تضمن رضاك التام',
                'items' => [
                    [
                        'icon' => 'fa-solid fa-truck-fast',
                        'title' => 'شحن سريع ومضمون',
                        'description' => 'توصيل لباب بيتك في أسرع وقت ممكن مع إمكانية تتبع شحنتك لحظة بلحظة.',
                    ],
                    [
                        'icon' => 'fa-solid fa-hand-holding-dollar',
                        'title' => 'الدفع عند الاستلام',
                        'description' => 'لا تدفع أي شيء إلا عند استلام منتجك والتأكد من جودته ومواصفاته بنفسك.',
                    ],
                    [
                        'icon' => 'fa-solid fa-shield-halved',
                        'title' => 'ضمان شامل وأصيل',
                        'description' => 'منتجاتنا أصلية 100% ومغطاة بضمان حقيقي للاستبدال والاسترجاع دون أي عناء.',
                    ],
                    [
                        'icon' => 'fa-solid fa-headset',
                        'title' => 'دعم فني متميز',
                        'description' => 'فريق خدمة عملاء جاهز للرد على استفساراتك ومساعدتك على مدار الساعة.',
                    ],
                ],
            ],
            [
                'type' => 'testimonials',
                'title' => 'آراء عملائنا السعداء ⭐',
                'subtitle' => 'تجارب حقيقية من عملاء وثقوا بنا واختبروا جودة منتجاتنا وخدماتنا المميزة',
                'items' => [
                    [
                        'name' => 'أحمد محمود',
                        'role' => 'عميل موثق',
                        'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop',
                        'rating' => 5,
                        'comment' => 'المنتج ممتاز جداً ووصلني في أقل من 48 ساعة. التغليف فاخر والخامات أفضل بكثير من المتوقع، شكراً جزيلاً على المصداقية!',
                    ],
                    [
                        'name' => 'سارة عبد الله',
                        'role' => 'عميلة موثقة',
                        'avatar' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=200&auto=format&fit=crop',
                        'rating' => 5,
                        'comment' => 'تجربة شراء رائعة ومميزة جداً. خدمة العملاء محترمين وسريعين في الرد، والمنتج يطابق الصور والمواصفات تماماً.',
                    ],
                    [
                        'name' => 'محمد كمال',
                        'role' => 'عميل موثق',
                        'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop',
                        'rating' => 5,
                        'comment' => 'أنصح بالتعامل معهم بشدة. أفضل سعر مقابل جودة في السوق وميزة الدفع عند الاستلام رたيحتني جداً في التعامل والأمان.',
                    ],
                ],
            ],
            [
                'type' => 'cta',
                'title' => 'هل أنت جاهز لاغتنام الفرصة الآن؟',
                'subtitle' => 'لا تفوت فرصة الحصول على خصم 50% والشحن المجاني قبل انتهاء العداد الزمني!',
                'button_text' => 'اطلب الآن واضغط هنا لتأكيد طلبك',
                'button_link' => '#product-showcase',
                'bg_color' => '#6c63ff',
            ],
        ];
    }

    public static function getAvailableTemplates(): array
    {
        return [
            'classic' => [
                'name' => 'القالب الكلاسيكي',
                'description' => '',
                'thumbnail' => '/images/templates/classic.png',
            ],
            'countdown' => [
                'name' => 'قالب العروض التنازلية',
                'description' => '',
                'thumbnail' => '/images/templates/countdown.png',
            ],
            'product_showcase' => [
                'name' => 'معرض المنتج المتكامل',
                'description' => '',
                'thumbnail' => '/images/templates/showcase.png',
            ],
            'product_detail' => [
                'name' => 'صفحة المنتج المباشرة',
                'description' => '',
                'thumbnail' => '/images/templates/detail.png',
            ],
        ];
    }
}
