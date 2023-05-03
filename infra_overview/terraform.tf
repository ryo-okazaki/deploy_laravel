terraform {
    required_providers {
        aws = {
            source = "hashicorp/aws"
            version = "~> 3.0"
        }
    }
}

provider "aws" {
    region = "ap-northeast-1"
}

resource "aws_iam_policy" "IAMManagedPolicy" {
    name = "ecsFargateExecRole"
    path = "/"
    policy = <<EOF
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "ssmmessages:CreateControlChannel",
                "ssmmessages:CreateDataChannel",
                "ssmmessages:OpenControlChannel",
                "ssmmessages:OpenDataChannel"
            ],
            "Resource": "*"
        }
    ]
}
EOF
}

resource "aws_ecr_repository" "ECRRepository" {
    name = "demo-nginx-ecs"
}

resource "aws_ecr_repository" "ECRRepository2" {
    name = "demo-laravel-ecs"
}

resource "aws_ecs_cluster" "ECSCluster" {
    name = "demo-laravel-ecs-cluster"
}

resource "aws_vpc" "EC2VPC" {
    cidr_block = "172.16.0.0/24"
    enable_dns_support = true
    enable_dns_hostnames = true
    instance_tenancy = "default"
    tags = {
        Name = "ecs-larave-vpc"
    }
}

resource "aws_vpc_endpoint" "EC2VPCEndpoint" {
    vpc_endpoint_type = "Gateway"
    vpc_id = "${aws_vpc.EC2VPC.id}"
    service_name = "com.amazonaws.ap-northeast-1.s3"
    policy = "{\"Version\":\"2008-10-17\",\"Statement\":[{\"Effect\":\"Allow\",\"Principal\":\"*\",\"Action\":\"*\",\"Resource\":\"*\"}]}"
    private_dns_enabled = false
}

resource "aws_security_group" "EC2SecurityGroup" {
    description = "demo-laravel-ecs-alb-sg"
    name = "demo-laravel-ecs-alb-sg"
    tags = {}
    vpc_id = "${aws_vpc.EC2VPC.id}"
    ingress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 443
        protocol = "tcp"
        to_port = 443
    }
    egress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 0
        protocol = "-1"
        to_port = 0
    }
}

resource "aws_security_group" "EC2SecurityGroup2" {
    description = "demo-laravel-ecs-web-sg"
    name = "demo-laravel-ecs-web-sg"
    tags = {}
    vpc_id = "${aws_vpc.EC2VPC.id}"
    ingress {
        security_groups = [
            "${aws_security_group.EC2SecurityGroup.id}"
        ]
        from_port = 80
        protocol = "tcp"
        to_port = 80
    }
    egress {
        cidr_blocks = [
            "0.0.0.0/0"
        ]
        from_port = 0
        protocol = "-1"
        to_port = 0
    }
}

resource "aws_lb_target_group" "ElasticLoadBalancingV2TargetGroup" {
    health_check {
        interval = 30
        path = "/login"
        port = "traffic-port"
        protocol = "HTTP"
        timeout = 5
        unhealthy_threshold = 2
        healthy_threshold = 5
        matcher = "200"
    }
    port = 80
    protocol = "HTTP"
    target_type = "ip"
    vpc_id = "${aws_vpc.EC2VPC.id}"
    name = "demo-laravel-ecs-tg"
}

resource "aws_route53_zone" "Route53HostedZone" {
    name = "laravel-sample.com."
}

resource "aws_route53_record" "Route53RecordSet" {
    name = "laravel-sample.com."
    type = "A"
    alias {
        name = "dualstack.demo-laravel-ecs-alb-73573798.ap-northeast-1.elb.amazonaws.com."
        zone_id = "Z14GRHDCWA56QT"
        evaluate_target_health = true
    }
    zone_id = "Z04915203PQF5PB1MWJUT"
}

resource "aws_route53_record" "Route53RecordSet2" {
    name = "laravel-sample.com."
    type = "NS"
    ttl = 172800
    records = [
        "ns-1462.awsdns-54.org.",
        "ns-376.awsdns-47.com.",
        "ns-2008.awsdns-59.co.uk.",
        "ns-723.awsdns-26.net."
    ]
    zone_id = "Z04915203PQF5PB1MWJUT"
}

resource "aws_route53_record" "Route53RecordSet3" {
    name = "laravel-sample.com."
    type = "SOA"
    ttl = 900
    records = [
        "ns-1462.awsdns-54.org. awsdns-hostmaster.amazon.com. 1 7200 900 1209600 86400"
    ]
    zone_id = "Z04915203PQF5PB1MWJUT"
}

resource "aws_acm_certificate" "CertificateManagerCertificate" {
    domain_name = "laravel-sample.com"
    subject_alternative_names = [
        "laravel-sample.com"
    ]
    validation_method = "DNS"
}

resource "aws_iam_role" "IAMRole" {
    path = "/"
    name = "ecsTaskExecutionRole"
    assume_role_policy = "{\"Version\":\"2012-10-17\",\"Statement\":[{\"Sid\":\"\",\"Effect\":\"Allow\",\"Principal\":{\"Service\":\"ecs-tasks.amazonaws.com\"},\"Action\":\"sts:AssumeRole\"}]}"
    max_session_duration = 3600
    tags = {}
}

resource "aws_iam_role" "IAMRole2" {
    path = "/"
    name = "ecsAutoScalingRole"
    assume_role_policy = "{\"Version\":\"2012-10-17\",\"Statement\":[{\"Sid\":\"\",\"Effect\":\"Allow\",\"Principal\":{\"Service\":\"application-autoscaling.amazonaws.com\"},\"Action\":\"sts:AssumeRole\"}]}"
    max_session_duration = 3600
    tags = {}
}

resource "aws_iam_role" "IAMRole3" {
    path = "/"
    name = "ecsRole"
    assume_role_policy = "{\"Version\":\"2008-10-17\",\"Statement\":[{\"Sid\":\"\",\"Effect\":\"Allow\",\"Principal\":{\"Service\":\"ecs.amazonaws.com\"},\"Action\":\"sts:AssumeRole\"}]}"
    max_session_duration = 3600
    tags = {}
}

resource "aws_iam_role" "IAMRole4" {
    path = "/"
    name = "ecsInstanceRole"
    assume_role_policy = "{\"Version\":\"2008-10-17\",\"Statement\":[{\"Sid\":\"\",\"Effect\":\"Allow\",\"Principal\":{\"Service\":\"ec2.amazonaws.com\"},\"Action\":\"sts:AssumeRole\"}]}"
    max_session_duration = 3600
    tags = {}
}
