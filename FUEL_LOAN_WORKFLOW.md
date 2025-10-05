# SunFuel Boda Boda Fuel Loan System - Workflow Documentation

## Overview
The SunFuel system enables Boda Boda riders to access fuel loans from designated petrol stations based on their assigned package and stage. The system operates on a daily loan cycle with specific time restrictions and mobile money payments.

## System Architecture

### Core Entities
1. **Boda Users** - Registered motorcycle taxi riders
2. **Fuel Stations** - Partner petrol stations with assigned agents
3. **Stages** - Designated pickup points for Boda riders
4. **Packages** - Loan amount tiers available to users
5. **Agents** - Fuel station staff who process fuel requests

## Workflow Process

### 1. User Activation & Setup
- Boda rider registers with personal details, NIN, and motorcycle information
- System assigns them to a stage and fuel station based on location
- User is assigned a loan package (determines maximum daily loan amount)
- Initial status: `0` (not activated)

### 2. Daily Fuel Loan Cycle

#### Morning Window (6:00 AM - 12:00 PM)
- **Eligibility Check**: User must have no outstanding loans from previous day
- **USSD Request**: User dials USSD code to request fuel activation
- **Code Generation**: System generates unique 6-digit activation code
- **SMS Notification**: User receives activation code via SMS

#### Fuel Collection Process
1. **Visit Station**: User goes to assigned fuel station
2. **Agent Portal**: Fuel station agent logs into portal
3. **Code Entry**: Agent enters user's activation code
4. **Validation**: System validates:
   - Code is valid and not expired
   - User has no outstanding loans
   - Station has sufficient fuel float
   - Request is within time window (6 AM - 12 PM)
5. **Fuel Dispensing**: Agent dispenses fuel to user
6. **Transaction Record**: System creates loan record
7. **SMS Confirmation**: User receives SMS confirming fuel received
8. **Float Update**: Station's fuel float is reduced by loan amount

#### Evening Payment Window (5:00 PM - 12:00 AM)
- **Payment Reminder**: Users receive SMS reminders at 5:00 PM
- **USSD Payment**: Users pay via USSD using mobile money
- **Payment Validation**: System validates payment amount and transaction
- **Loan Closure**: Outstanding loan is marked as paid
- **Next Day Eligibility**: User becomes eligible for next day's loan

### 3. Time Restrictions
- **Borrowing Window**: 6:00 AM - 12:00 PM (6 hours)
- **Payment Window**: 5:00 PM - 12:00 AM (7 hours)
- **Grace Period**: Midnight to 6:00 AM (system maintenance)

### 4. Loan Management
- **Daily Limit**: Based on user's package
- **Interest**: Configurable interest rate per loan
- **Penalty**: Late payment penalties
- **Suspension**: Users with unpaid loans are blocked from new loans

## Database Schema Extensions

### New Tables Required
1. **fuel_activation_codes** - Temporary codes for fuel requests
2. **fuel_loans** - Individual fuel loan transactions
3. **fuel_station_float** - Real-time fuel inventory tracking
4. **ussd_sessions** - USSD interaction tracking
5. **sms_logs** - SMS notification history

### Enhanced Existing Tables
1. **bodauser** - Add package assignment and loan eligibility fields
2. **fuelstation** - Add float management fields
3. **package** - Add time restrictions and interest rates

## API Endpoints

### USSD Endpoints
- `POST /api/ussd/fuel-request` - Generate fuel activation code
- `POST /api/ussd/payment` - Process mobile money payment
- `GET /api/ussd/balance` - Check outstanding loan balance

### Agent Portal Endpoints
- `POST /api/agent/activate-fuel` - Process fuel activation code
- `GET /api/agent/pending-requests` - List pending fuel requests
- `GET /api/agent/float-status` - Check station fuel float

### Admin Endpoints
- `GET /api/admin/loan-reports` - Generate loan reports
- `POST /api/admin/float-management` - Manage station floats
- `GET /api/admin/user-eligibility` - Check user loan eligibility

## Security Features
- Activation codes expire after 30 minutes
- Rate limiting on USSD requests
- SMS verification for all transactions
- Audit logs for all fuel transactions
- Real-time fraud detection

## SMS Templates
1. **Activation Code**: "Your fuel activation code is [CODE]. Valid for 30 minutes. Visit [STATION_NAME]."
2. **Fuel Received**: "You have received [AMOUNT] worth of fuel. Pay [TOTAL_AMOUNT] by midnight to qualify for tomorrow's loan."
3. **Payment Reminder**: "Please pay your outstanding loan of [AMOUNT] by midnight to qualify for tomorrow's fuel loan."
4. **Payment Confirmed**: "Payment of [AMOUNT] confirmed. You are eligible for tomorrow's fuel loan."

## Error Handling
- Invalid activation codes
- Expired codes
- Insufficient station float
- User eligibility violations
- Payment processing failures
- SMS delivery failures

## Monitoring & Analytics
- Daily loan volumes
- Payment completion rates
- Station float utilization
- User behavior patterns
- Revenue tracking
- Default rates

## Configuration Parameters
- Maximum daily loan amount per package
- Interest rates per package
- Penalty rates for late payments
- Activation code expiry time
- SMS delivery timeouts
- Float replenishment triggers

## Future Enhancements
- Credit scoring system
- Dynamic loan limits based on payment history
- Multi-station access for users
- Integration with fuel suppliers
- Mobile app for users and agents
- Advanced analytics dashboard
