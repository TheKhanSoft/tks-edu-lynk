<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Super Admin Seeding
         User::factory()->withPersonalTeam()->create([
            'name' => "Kashif Ahmad Khan",
            'email' => "superadmin@thekhansoft.com",
            'password' => Hash::make('SuperAdmin@2009'),
        ])->assignRole('Super Admin');
        // User::create([
        //     'name' => "Kashif Ahmad Khan",
        //     'email' => "superadmin@thekhansoft.com",
        //     'password' => Hash::make('SuperAdmin@2009'),
        // ])->assignRole('Super Admin');

        //Admins Seeding
        $admins = [
            [
                "name" => "Rayyan Ahmad Khan",
                "email" => "rayyan@thekhansoft.com",
                'password' => Hash::make('admin@1234'),
            ],
            [
                "name" => "Hoora Shehzadi Ahmad Khan",
                "email" => "hoora_shehzadi@thekhansoft.com",
                'password' => Hash::make('admin@1234'),
            ],
            [
                "name" => "admin",
                "email" => "admin@thekhansoft.com",
                'password' => Hash::make('admin@1234'),
            ],
        ];
        foreach($admins as $admin){
            User::create($admin)->assignRole('Admin');
        };

        
        //Principal
        $principals = [
            ["Ms. Saima Rafiq", "khatoonnaina362@gmail.com"],
            ["Ms. Hina Rahman", "hiniaaqib@gmail.com"],
            ["Ms. Jawariya Mobin", "mueedkhan099@gmail.com"],
            ["Mr. Usman Ali", "usmanphy@gmail.com"],
            ["Mr. Aamir Sohail Akhthar", "aamirsohailakhtar1996@gmail.com"],
            ["Mr. Hamd Ullah", "hamdullah1801111@gmail.com"],
            ["Ms. Mah e Neema Alam", "maheneemaalam2611@gmail.com"],
            ["Mr. Badar Zaman", "badarzamanrosht@gmail.com"],
            ["Mr. Syed Abdullah", "syedabdullah0343@gmail.com"],
            ["Mr. Mashal Shah", "mashalshah20@gmail.com"],
        ];
        foreach ($principals as $principal) {
            User::create(
                [
                    "name" => $principal[0],
                    "email" => ucwords($principal[1]),
                    'password' => Hash::make('principal1234')
                ]
            )->assignRole('Principal');
        }

        //Vice Principal
        $vice_principals = [
            ["Mr. Salman Khan", "zamarakhan01@gmail.com"],
            ["Mr. Hamid Ali", "hamidali00770@gmail.com"],
            ["Mr. Uzair Muhammad", "uzairmuhammad127@gmail.com"],
            ["Ms. Ghazala Aman", "ghazalyzi04@gmail.com"],
            ["Ms. Nadia Aman", "nadiaaman49@gmail.com"],
            ["Mr. Khan Jehangeer khan", "jk369861@gmail.com"],
            ["Ms. Maryam Sajjad", "marymsajjad@gmail.com"],
            ["Ms. Aisha Gul", "agul17712@gmail.com"],
            ["Ms. Usama Amjad", "Oamjad25@gmail.com"],
            ["Ms. Jalwa sahar", "khansahar652@gmail.com"],

        ];
        foreach ($vice_principals as $v_principal) {
            User::create(
                [
                    "name" => $v_principal[0],
                    "email" => ucwords($v_principal[1]),
                    'password' => Hash::make('vice_principal1234')
                ]
            )->assignRole('Vice Principal');
        }


        //Staff Members
        $staff_members = [
            [ "Mr. Shah Hussain Ali", "shah.hussain@gmail.com",],
            [ "Mr. Haseena Khan","haseena@gmail.com",],
            [ "Ms. Tayyeba","tayyeba@gmail.com",],
            [ "Mr. Khuram Shehzad", "khuram@gmail.com",],
            ["Mr. Waqar Ali", "waqar667617@gmail.com",],
            ["Mr. Muhammad Amir", "klashankov432@gmail.com",],
            ["Ms. Khadija Anum", "khadijaanum1610@gmail.com",],
            ["Mr. Atta Ullah", "attaullahshah257@gmail.com",],
            ["Mr. Javid Ullah", "javidamir611@gmail.com",],
            ["Mr. Saifullah Jan", "saifjan2210@gmail.com",],
            ["Mr. Shehzad Khan", "shehzadkhan876@gmail.com",],
            ["Mr. Fazal Wahid", "fw277444@gmail.com",],
            ["Mr. Hamza Bilal", "hb851719@gmail.com",],
            ["Mr. SAIMA ALI", "homep4485@gmail.com",],
            ["Mr. Abid khan", "abidkhangarhi0909@gmail.com",],
            ["Mr. IZAZ AHMAD", "izazphysics822@gmail.com",],
            ["Mr. Muhammad Zeshan", "defendermiroak@icloud.com",],
            ["Mr. Muhammad Nadeem khan", "nkhan20199@gmail.com",],
            ["Mr. Ayaz ali shah", "ayazalishah3637@gmail.com",],
            ["Mr. Tariq Mahmood", "tariqmahmoodchem1144@gmail.com",],
            ["Ms. Syyeda Uroosa Ibrar", "Uroosaibrar7@gmail.com",],
            ["Mr. Syed mir afzal Shah", "afzalmir888@gmail.com",],
            ["Ms. SAKINA", "sakeenaahmad1996@gmail.com",],
            ["Ms. Nitasha Irum", "nitashairum@gmail.com",],
            ["Ms. Saba Rehman", "usamakhalid5710@gmail.com",],
            ["Mr. Hazrat Hayat", "hayathazrat@gmail.com",],
            ["Mr. Ihtisham haq", "ihtishamu89@gmail.com",],
        ];
        foreach($staff_members as $staff_member){
             User::create(
                [
                    "name" => $staff_member[0],
                    "email" => ucwords($staff_member[1]),
                    'password' => Hash::make('staff1234')
                ]
            )
            ->assignRole('Staff Member');
        }

        //Faculty members
        $faculty_members = [
            ["Mr. Faiq Umar", "faiqumar555@gmail.com",],
            ["Mr. Masaood Khan", "shanmasaood2023@gmail.com",],
            ["Mr. Muhammad Osama", "mosama8898@gmail.com",],
            ["Mr. Abubakkar Siddique", "bakar9039170@gmail.com",],
            ["Mr. Zakir ullah", "zakirbilal0000@gmail.com",],
            ["Ms. Sidra Rahman", "sidrarahman222@gmail.com",],
            ["Ms. Hira Haleem", "hirahaleem2001@gmail.com",],
            ["Mr. muhammad talha", "mt7081696@gmail.com",],
            ["Mr. SALAH UD DIN", "salahuddinjr@gmail.com",],
            ["Mr. Syed Mazhar Ali Shah", "mazharali79@yahoo.com",],
            ["Ms. Wareesha Jabbar", "wareeshajabbar925@gmail.com",],
            ["Mr. Manan Bacha", "manansyed67@gmail.com",],
            ["Ms. gullalay", "ammarps2@gmail.com",],
            ["Mr. muhammad numan", "muhammadnumanutmani@gmail.com",],
            ["Ms. Asma Hazrat", "asmajawad3@gmail.com",],
            ["Ms. Arooj", "ojkhan8@gmail.com",],
            ["Ms. Rukhsar Sikandar", "sikandarrukhsar1@gmail.com",],
            ["Ms. Huma Haroon", "humaharoon637@gmail.com",],
            ["Ms. TANZEELA", "kaleemullahdurani@gmail.com",],
            ["Mr. Asad Zaman", "asadzaman333@gmail.com",],
            ["Ms. Sumbal Khan Kakar", "sumbalkakar99@gmail.com",],
            ["Mr. Maimoona kalsoom", "monaawan71@gmail.com",],
            ["Ms. Javaria Qamar", "javariaqamar99@gmail.com",],
            ["Mr. Nauman Amjid", "naumanamjid17@gmail.com",],
            ["Mr. Naqeeb Ullah", "khannaqeeb235@gmail.com",],
            ["Ms. arooba", "bilalkhandmi640@gmail.com",],
            ["Mr. ABDUL BASIT", "yqulangi@gmail.com",],
            ["Mr. Musa Khan", "swatpk4567@gmail.com",],
            ["Ms. Amna Jan", "amna66389@gmail.com",],
            ["Ms. Shaista Naz", "shaistanaz319@gmail.com",],
            ["Ms. Asma asma", "asmaullhussnahussna@gmail.com",],
            ["Mr. Abdur Rafay Malik", "rafaymalik50@gmail.com",],
            ["Mr. Muhammad Aaqib", "muhammadaaqib125122@gmail.com",],
            ["Mr. Hasnain Riaz", "khattakg656@gmail.com",],
            ["Mr. Hashim Sultan Khan", "hashimsultan19@gmail.com",],
            ["Mr. Shah Faisal", "faisalmashallfaisal@gmail.com",],
            ["Ms. Sadia Bibi", "ksadi9724@gmail.com",],
            [ "Ms.Bushra Bibi", "111uzairgul@gmail.com", ],
            [ "Mr.Irfan Ali khan", "mirwaskhel@gmail.com", ],
            [ "Ms.Syeda Iqra Irshad", "syedaiqrairshad94@gmail.com", ],
            [ "Ms.UZAIMA TAHIR DURANI", "ihteshampervez.nsr@gmail.com", ],
            [ "Mr.Said ul Abrar Abrar", "sa03459364163@gmail.com", ],
            [ "Mr.Aziz Muhammad Khan", "azizbacha836@gmail.com", ],
            [ "Ms.Alina Alina", "aleenashahrox@gmail.com", ],
            [ "Ms.Safia Ali", "Safiakhan686@gmail.com", ],
            [ "Ms.Mustafa Kamal", "mustafakamal1032002@gmail.com", ],
            [ "Ms.Huma Gul", "haya9t5@gmail.com", ],
            [ "Ms.Iram Khan", "ik8811369@gmail.com", ],
            [ "Ms.Nida Bibi", "nidakhan8642@gmail.com", ],
            [ "Ms.Kainat Akhtar", "janan.meher@gmail.com", ],
            [ "Ms.Komal Iqra", "fa7913520@gmail.com", ],
            [ "Mr.YAQUB KHAN", "YAQUBK040@GMAIL.COM", ],
            [ "Ms.Rahab Karim", "rhbkhani65@gmail.com", ],
            [ "Ms.Sawaira Hayat", "Sawairahayat1900@gmail.com", ],
            [ "Ms.Atia Naz", "atianazchemistry@gmail.com", ],
            [ "Ms.Syeda Safa Tahoor", "saffaasjad@gmail.com", ],
            [ "Ms.Hifsa malik", "malikhifsa1@gmail.com", ],
            [ "Ms.Haseena Jamal", "haseebshah333333@gmail.com", ],
            [ "Ms.Benish Khushmir", "khushmirbenish@gmail.com", ],
            [ "Ms.Rubeena Khan", "khanrubeena82@gmail.com", ],
            [ "Mr.Afsar Ali", "aliofficial7746@gmail.com", ],
            [ "Ms.Khadija Shakoor", "khadijashakoor701@gmail.com", ],
            [ "Mr.Naeem Ullah", "naeem940ullah@gmail.com", ],
            [ "Mr.Salman Yousaf", "albert442000@gmail.com", ],
            [ "Mr.Izaz Ullah", "izaz21511@gmil.com", ],
            [ "Ms.Rahat Bano", "rahataltaf29@gmail.com", ],
        ];

        foreach($faculty_members as $faculty_member){
             User::create(
                [
                    "name" => $faculty_member[0],
                    "email" => ucwords($faculty_member[1]),
                    'password' => Hash::make('faculty1234')
                ]
            )
            ->assignRole('Faculty Member');
        }


        $faculty_members2 = [
            "Ms. Qamar Jabeen","Mr. Sultana Ayaz", "Ms. Humaira Shah", "Mr. Hamraz Khan", "Mr. Kashif Ali",
            "Mr. Faheem Ullah", "Mr. Kamran Bangash", "Ms. Shagufta", "Ms. Jalwa GUl", "Mr. Abbas Sarfaraz",
            "Mr. Mujahid Ali","Mr. Kausar Shah", "Mr. Laiba Ali", "Mr. Khan Zaman", "Mr. Shah Hussain Ali",
            "Mr. Hamid", "Mr. Hadi Razwi", "Mr. Muhammad Ali", "Mr. Raza Ullah", "Mr. Majid Ali", 
            "Ms. Maira Quraishi", "Ms. Taha Khan", "Ms. Nurgas Murtaza", "Mr. Jalal Ud Din", "Ms. Komal Jamil",
            'Mr. Zarak Khan'
        ];

        foreach($faculty_members2 as $faculty){
            $newFaculty = ['name' => ucwords($faculty), 
            'email' => fake()->unique()->safeEmail(), 
            'password' => Hash::make('faculty123')];
            User::create($newFaculty)
                ->assignRole('Faculty Member');
        }

        $accountants = [
            ["Ms. AZRA BIBI", "az9354836@gmail.com",],
            ["Mr. Umar Zaman", "uzn1833@gmail.com",],
            ["Mr. Muhammad Shayan", "hk8708084@gmail.com",],
            ["Ms. Kainat Khan", "Kainatkhanlibra@gmail.com",],
            ["Mr. Muhammad Hamza", "hamzasahibzada55@gmail.com",],
            ["Ms. Sanam Sikandar", "sanansikandar14@gmail.com",],
            ["Ms. Zarqa", "zarqakareem@gmail.com",],
            ["Ms. Raina Dilawar", "imadaminmrd@gamail.com",],
            ["Ms. Sawaira Begum", "sawaira12345@gmail.com",],
            ["Ms. Sadaf  Riaz", "Sadafr363@gmail.com",],
        ];

        foreach($accountants as $accountant){
             User::create(
                [
                    "name" => ucwords($accountant[0]),
                    "email" => $accountant[1],
                    'password' => Hash::make('accountant1234')
                ]
            )
            ->assignRole('Accountant');
        }
       

        
    }
}
