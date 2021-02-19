<?php


namespace App\Enums;


class SystemEnums
{
    const UserTypes = ['manager', 'employee'];
    const UserIsManager = 'manager';
    const UserIsEmployee = 'employee';
    const Status = ['pending', 'approve', 'reject', 'cancel'];
    const StatusIsPending = 'pending';
    const StatusIsApproved = 'approve';
    const StatusIsRejected = 'reject';
    const StatusIsCanceled = 'cancel';
}
