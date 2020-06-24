<?php

declare(strict_types=1);

use Andali\Companies\Models\Company;
use Andali\Companies\Models\CompanyInvitation;
use Andali\Companies\Models\CompanyMember;

return [

    'models' => [

        /*
         * When using the "HasTeams" trait from this package, we need to
         * know which Eloquent model should be used to retrieve your teams.
         *
         * The model you want to use as a Company model needs to implement the
         * `KodeKeep\Teams\Contracts\Company` contract.
         */

        'company' => Company::class,

        /*
         * When using the "HasTeams" trait from this package, we need to
         * know which Eloquent model should be used to retrieve your team members.
         *
         * The model you want to use as a Company model needs to implement the
         * `KodeKeep\Teams\Contracts\CompanyMember` contract.
         */

        'member' => CompanyMember::class,

        /*
         * When using the "HasTeams" trait from this package, we need to
         * know which Eloquent model should be used to retrieve your team invitations.
         *
         * The model you want to use as a Company model needs to implement the
         * `KodeKeep\Teams\Contracts\CompanyInvitation` contract.
         */

        'invitation' => CompanyInvitation::class,

        /*
         * When using the "HasTeams" trait from this package, we need to
         * know which Eloquent model should be used to create relationships for
         * teams and all of their team members through a pivot table.
         */

        'user' => 'App\Models\User',

    ],

    'tables' => [

        /*
         * When using the "HasTeams" trait from this package, we need to know which
         * table should be used to retrieve your teams. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'companies' => 'companies',

        /*
         * When using the "HasTeams" trait from this package, we need to know which
         * table should be used to retrieve your members. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'members' => 'company_users',

        /*
         * When using the "HasTeams" trait from this package, we need to know which
         * table should be used to retrieve your invitations. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'invitations' => 'company_invitations',

        /*
         * When using the "HasTeams" trait from this package, we need to know which
         * table should be used to retrieve your users. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'users' => 'users',

    ],

];
