<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    protected const DRAFT_NOTICE = '<p><strong>مسودة:</strong> هذا نص مؤقت وضعه فريق التطوير، ويجب على المؤسسة استبداله بالنص المعتمد من لوحة التحكم قبل إطلاق الموقع.</p>';

    /**
     * Created only when missing, so edits made in the admin panel survive a re-seed.
     */
    public function run(): void
    {
        $pages = [
            'vision' => [
                'title' => 'الرؤية',
                'body' => '<p>أن تكون المؤسسة مرجعًا رائدًا في جمع وتوثيق وإحياء التراث الفاطمي والشيعي، وأن تسهم في تعزيز الوعي الثقافي والتاريخي بين الباحثين والمهتمين في العالم العربي.</p>',
            ],
            'mission' => [
                'title' => 'الرسالة',
                'body' => '<p>الحفاظ على تراث الصديقة الزهراء عليها السلام وإحياؤه، من خلال جمعه وتوثيقه وأرشفته ونشره، ودعم الدراسات والأبحاث المرتبطة به، وإيصاله إلى الأجيال الجديدة من الباحثين والمهتمين.</p>',
            ],
            'privacy' => [
                'title' => 'سياسة الخصوصية',
                'body' => self::DRAFT_NOTICE.'<p>تلتزم المؤسسة بحماية بيانات زوار الموقع، ولا تُستخدم البيانات المرسلة عبر نموذج التواصل إلا للرد على أصحابها.</p>',
            ],
            'terms' => [
                'title' => 'شروط الاستخدام',
                'body' => self::DRAFT_NOTICE.'<p>المواد المنشورة في هذا الموقع مخصصة للاستخدام العلمي والثقافي غير التجاري، مع ضرورة الإشارة إلى المصدر عند الاقتباس.</p>',
            ],
            'rights' => [
                'title' => 'حقوق النشر',
                'body' => self::DRAFT_NOTICE.'<p>جميع الحقوق محفوظة لمؤسسة إحياء تراث الصديقة الشهيدة (ع) ما لم يُذكر خلاف ذلك على المادة نفسها.</p>',
            ],
        ];

        $order = 0;

        foreach ($pages as $slug => $page) {
            Page::firstOrCreate(
                ['slug' => $slug],
                $page + ['status' => 'published', 'sort_order' => ++$order],
            );
        }
    }
}
